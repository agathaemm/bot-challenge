<?php

namespace App\Library\Pdf;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use App\Exports\PdfExport;
use Spatie\PdfToText\Pdf;

class PdfToExcel
{
    /**
     * Protocol fields
     * 
     * @var array
     */
    protected $protocolFiels = [
        "1 - Registro ANS",
        "3 - Nome da Operadora",
        "4 - CNPJ da Operadora",
        "5 - Data de Emissão",
        "6 - Código na Operadora",
        "7 - Nome do Contratado",
        "8 - Código CNES",
        "9 - Número do Lote",
        "10 - Nº do Protocolo (Processo)",
        "11- Data do Protocolo",
        "12 - Código da Glosa do Protocolo",
        "38 - Valor Informado do Protocolo (R$)",
        "39 - Valor Processado do Protocolo (R$)",
        "40 - Valor Liberado do Protocolo (R$)",
        "41 - Valor Glosa do Protocolo (R$)",
        "42 - Valor Informado Geral (R$)",
        "43 - Valor Processado Geral (R$)",
        "44 - Valor Liberado Geral (R$)",
        "45 - Valor Glosa Geral (R$)"
    ];

    /**
     * Beneficiary fields
     * 
     * @var array
     */
    protected $beneficiaryFields = [
        "13 - Número da Guia no Prestador",
        "14 - Número da Guia Atribuido pela Operadora",
        "15 - Senha",
        "16 - Nome do Beneficiário",
        "17 - Número da Carteira",
        "18 - Data Início do Faturamento",
        "19 - Hora Início do Faturamento",
        "20 - Data Fim do Faturamento",
        "21 - Hora Fim do Faturamento",
        "22 - Código da Glosa da Guia"
    ];

    /**
     * Beneficiary services fields
     * 
     * @var array
     */
    protected $beneficiaryServicesFields = [
        "23 - Data de realização",
        "24 - Tabela",
        "25 - Código Procedimento",
        "26 - Descrição",
        "27 - Grau de Participação",
        "28 - Valor Informado",
        "29 - Quant. Executada",
        "30 - Valor Processado",
        "31 - Valor Liberado",
        "32 - Valor Glosa",
        "33 - Código da Glosa"
    ];

    /**
     * Beneficiary total values fields
     * 
     * @var array
     */
    protected $beneficiaryTotalValues = [
        "34 - Valor Informado da Guia (R$)",
        "35 - Valor Processado da Guia (R$)",
        "36 - Valor Liberado da Guia (R$)",
        "37 - Valor Glosa da Guia (R$)"
    ];


    /**
     * PdfToExcel constructor
     * 
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Clean pdf lines
     * 
     * @param $text
     * @return array
     */
    public function cleanLines($text)
    {
        $previous = null;
        $lines = collect(explode("\n", $text))
            ->map(fn ($item) => trim(trim(preg_replace('/\s\s+/', "|||", $item)), '|||'))
            ->filter(function ($item) use (&$previous) {
                if ($previous === "" && $item === "") {
                    return false;
                }
                $previous = $item;

                return true;
            });
        return $lines;
    }

    /**
     * Fix a specific line
     * 
     * @param $lineCols
     * @return array
     */
    public function fixLine($lineCols)
    {
        $cols = [];
        foreach ($lineCols as $lineCol) {
            switch ($lineCol) {
                case '23 - Data de':
                    $cols[] = '23 - Data de realização';
                    break;
                case '24 -':
                    $cols[] = '24 - Tabela';
                    break;
                case '25 - Código Procedimento 26 - Descrição':
                    $cols[] = '25 - Código Procedimento';
                    $cols[] = '26 - Descrição';
                    break;
                case '27 -Grau de 28 - Valor Informado 29 -Quant. 30 - Valor Processado 31 - Valor Liberado 32 - Valor Glosa':
                    $cols[] = '27 - Grau de Participação';
                    $cols[] = '28 - Valor Informado';
                    $cols[] = '29 - Quant. Executada';
                    $cols[] = '30 - Valor Processado';
                    $cols[] = '31 - Valor Liberado';
                    $cols[] = '32 - Valor Glosa';
                    break;
                case '33 - Código da Glosa':
                    $cols[] = '33 - Código da Glosa';
                    break;
                default:
                    $cols[] = $lineCol;
                    break;
            }
        }
        return $cols;
    }

    /**
     * Return pdf current page
     * 
     * @param $page
     * @param $currentPage
     * @return string
     */
    public function getCurrentPage($page, $currentPage)
    {
        if (!str_contains($page, 'Pág.:')) return $currentPage;
        return $page;
    }

    /**
     * Remove pdf useless lines
     * 
     * @param $lines
     * @return array
     */
    public function removeUselessLines($lines)
    {
        $lines = $lines->filter(function ($line) {
            if ($line == "") return false;
            if ($line == "realização|||Tabela /Item assistencial|||Participação|||Executada") return false;
            if ($line == "DECOH - Departamento de Contas Médicas | CT554|||Impressão - Portal do Prestador") return false;
            $lineArray = explode('|||', $line);
            if (count($lineArray) == "1" && !str_contains($line, 'Pág.:')) return false;
            return true;
        });
        return $lines;
    }

    /**
     * Verify if passed line is a title
     * 
     * @param $line
     * @return boolean
     */
    public function isTitleLine($line)
    {
        $itens = explode(' ', $line);
        if (is_numeric($itens[0]) && isset($itens[1]) && $itens[1] == '-') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verify if passed line is a value
     * 
     * @param $line
     * @param $previousTitle
     * @return boolean
     */
    public function isValueLine($line, $previousTitle)
    {
        $values = explode("|||", $line);
        if (count($values) <= count($previousTitle) && !str_contains($line, "Pág.:")) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Fix protocol number
     * 
     * @param $values
     * @return array
     */
    public function fixNumProtocoloValue($values)
    {
        $valuesCollection = collect($values);
        if (!$valuesCollection->contains("(NDS)")) return $values;
        $valuesCollection->each(function ($value, $key) use (&$values) {
            if ($value == "(NDS)") {
                $values[$key - 1] .= ' ' . $value;
                $values[$key] = $values[$key + 1];
                unset($values[$key + 1]);
            }
        });
        return $values;
    }

    /**
     * Fix the beneficiary's last health array item value
     * 
     * @param $valuesCols
     * @return array
     */
    public function fixBeneficiarioLastValue($valuesCols)
    {
        // Make a collection os values
        $valuesCollection = collect($valuesCols);

        // Get last array item
        $lastItem = $valuesCollection->last();

        // Separate the two values
        $lastItens = Str::of($lastItem)->explode(" ");

        // Remove collection last item
        $valuesCollection->pop();

        // Add the two values at the end of the collection
        $lastItens->each(function ($value) use ($valuesCollection) {
            $valuesCollection->push($value);
        });

        // Returns fixed values
        return $valuesCollection->toArray();
    }

    /**
     * Corrects the values of the beneficiaries' health array
     * 
     * @param $values
     * @return array
     */
    public function fixBeneficiarioOrderValues($values)
    {
        $newArrayValues = [];
        $count = 1;
        foreach ($values as $value) {
            switch ($count) {
                case '3':
                    // assign value to field Código Procedimento
                    $newArrayValues[] = "";
                    $newArrayValues[] = $value;
                    break;
                case '4':
                    // assign value to field Grau de participação
                    $newArrayValues[] = "";
                    $newArrayValues[] = $value;
                    break;
                default:
                    $newArrayValues[] = $value;
                    break;
            }
            $count++;
        }
        return $newArrayValues;
    }

    /**
     * Corrects the value of the beneficiaries
     * 
     * @param $titleLine
     * @param $valuesCols
     * @return array
     */
    public function fixBeneficiarioValues($titleLine, $valuesCols)
    {
        // Filters only by health data
        if ($titleLine != "23 - Data de|||24 -|||25 - Código Procedimento 26 - Descrição|||27 -Grau de 28 - Valor Informado 29 -Quant. 30 - Valor Processado 31 - Valor Liberado 32 - Valor Glosa|||33 - Código da Glosa") {
            return $valuesCols;
        }
        // Fix the beneficiary's last health array item value
        $valuesCols = $this->fixBeneficiarioLastValue($valuesCols);

        // Corrects the values of the beneficiaries' health array
        return $this->fixBeneficiarioOrderValues($valuesCols);
    }

    /**
     * Groups array items according to key and value
     * 
     * @param $pdfItensArrays
     * @return array
     */
    public function groupArrays($pdfItensArrays)
    {
        $values = [];

        // Scroll through arrays
        foreach ($pdfItensArrays as $pdfItensArray) {

            // Separates the title part from the values
            $titlesArray = $pdfItensArray["title"];
            $valuesArray = $pdfItensArray["values"];

            // Scroll through each value
            foreach ($valuesArray as $valueArray) {
                $value = array();

                // For each value add the corresponding key
                foreach ($titlesArray as $key => $title) {
                    $value[$title] = isset($valueArray[$key]) ? $valueArray[$key] : "";
                }
                $values[] = $value;
            }
        }
        return $values;
    }

    /**
     * Get protocol data
     * 
     * @param $itensPdf
     * @return array
     */
    public function getProtocol($itensPdf)
    {
        $protocolo = [];
        foreach ($itensPdf as $itens) {
            foreach ($itens as $title => $value) {
                if (collect($this->protocolFiels)->contains($title)) {
                    $protocolo[$title] = $value;
                } else {
                    continue;
                }
            }
        }
        return $protocolo;
    }

    /**
     * Get beneficiaries details
     * 
     * @param $itensPdf
     * @return array
     */
    public function getBeneficiaries($itensPdf)
    {
        // Initialize beneficiaries array
        $beneficiaries = [];

        // Initialize current beneficiary
        $beneficiario = "";

        // Scroll through pdf items
        foreach ($itensPdf as $itens) {

            // Initialize auxiliary variables
            $dados = [];
            $saude = [];
            foreach ($itens as $title => $value) {

                // Get the beneficiary
                if ($title == "13 - Número da Guia no Prestador") {
                    $beneficiario = $value;
                }

                // Get the rest of the beneficiary's data
                if (collect($this->beneficiaryFields)->contains($title)) {
                    $dados[$title] = $value;
                }

                // Get the total amounts of the beneficiary
                if (collect($this->beneficiaryTotalValues)->contains($title)) {
                    $dados[$title] = $value;
                }

                // Get the health data
                if (collect($this->beneficiaryServicesFields)->contains($title)) {
                    $saude[$title] = $value;
                }
            }

            // Set beneficiary's data
            if (count($dados) > 0) {
                $aux = isset($beneficiaries[$beneficiario]) ? $beneficiaries[$beneficiario] : [];
                $beneficiaries[$beneficiario] = array_merge($aux, $dados);
            }

            // Set health data
            if (count($saude) > 0) {
                $beneficiaries[$beneficiario]['saude'][] = $saude;
            }
        }
        return $beneficiaries;
    }

    /**
     * Export pdf to excel
     * 
     * @param $protocol
     * @param $beneficiaries
     */
    public function exportPdf($protocol, $beneficiaries)
    {
        return Excel::download(new PdfExport($protocol, $beneficiaries), 'demonstrative.xlsx');
    }

    /**
     * Extract data from pdf
     * 
     * @return boolean
     */
    public function extractPdfData($pdfFile)
    {
        // Convert pdf to teste
        $text = Pdf::getText($pdfFile, '/usr/local/bin/pdftotext', ['layout']);

        // Clean pdf lines
        $lines = $this->cleanLines($text);

        // Remove useless lines
        $lines = $this->removeUselessLines($lines);

        // Initialize pdf itens array
        $pdfItensArray = [];

        // Initialize pdf page
        $currentPage = "Pág.: 0001 / 0054";

        // Initialize support variables 
        $previousTitle = [];
        $previousTitleKey = 0;
        $previousTitleLine = "";

        // Run through the lines of the pdf
        foreach ($lines as $line) {

            // It does not allow entering the 2nd page.
            $currentPage = $this->getCurrentPage($line, $currentPage);
            if ($currentPage == "Pág.: 0002 / 0054") continue;

            // Turn the line into an array
            $lineInArray = collect(explode('|||', $line));

            // Check if the line is the title
            if ($this->isTitleLine($line)) {

                // Fix the columns of a specific row
                if ($line == "23 - Data de|||24 -|||25 - Código Procedimento 26 - Descrição|||27 -Grau de 28 - Valor Informado 29 -Quant. 30 - Valor Processado 31 - Valor Liberado 32 - Valor Glosa|||33 - Código da Glosa") {
                    $lineInArray = $this->fixLine($lineInArray);
                }

                // Check if the title has changed, if so, change the array position
                if ($previousTitleLine != $line) {
                    $previousTitleKey++;
                }

                // Add title to array
                $pdfItensArray[$previousTitleKey]['title'] = $lineInArray;

                // Keep the current title
                $previousTitle = $lineInArray;

                // Keep the current title line
                $previousTitleLine = $line;
            } elseif (count($previousTitle) > 0) {

                // Search the lines that are values
                if ($this->isValueLine($line, $previousTitle)) {

                    // Fix the protocol number
                    $this->fixNumProtocoloValue($lineInArray);

                    // Corrects the beneficiary values
                    $lineInArray = $this->fixBeneficiarioValues($previousTitleLine, $lineInArray);

                    // Store the values in the array in the corresponding position
                    $pdfItensArray[$previousTitleKey]['values'][] = $lineInArray;
                }
            }
        }

        // Group the extracted data
        $itensPdfGrouped = $this->groupArrays($pdfItensArray);

        // Search the protocol
        $protocol = $this->getProtocol($itensPdfGrouped);

        // Search beneficiary data
        $beneficiaries = $this->getBeneficiaries($itensPdfGrouped);

        // Export
        $this->exportPdf($protocol, $beneficiaries);

        // Retorna true as default
        return true;
    }
}
