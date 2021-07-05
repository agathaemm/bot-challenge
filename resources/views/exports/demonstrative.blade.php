<table>
    <thead>
        <tr>
            <th>Registro ANS</th>
            <th>Nome da Operadora</th>
            <th>Código na Operadora</th>
            <th>Nome do Contratado</th>
            <th>Número do Lote</th>
            <th>Número do Protocolo</th>
            <th>Data do Protocolo</th>
            <th>Código da Glosa do Protocolo</th>
            <th>Número da Guia no Prestador</th>
            <th>Número da Guia Atribuído pela Operadora</th>
            <th>Senha</th>
            <th>Nome do Beneficiário</th>
            <th>Número da Carteira</th>
            <th>Data Inicio do faturamento</th>
            <th>Hora Inicio do Faturamento</th>
            <th>Data Fim do Faturamento</th>
            <th>Código da Glosa da Guia</th>
            <th>Data de realização</th>
            <th>Tabela</th>
            <th>Codigo do Procedimento</th>
            <th>Descricao</th>
            <th>Grau Participação</th>
            <th>Valor Informado</th>
            <th>Quanti. Executada</th>
            <th>Valor Processado</th>
            <th>Valor Liberado</th>
            <th>Valor Glosa</th>
            <th>Código da Glosa</th>
            <th>Valor Informado da Guia</th>
            <th>Valor Processado da Guia</th>
            <th>Valor Liberado da Guia</th>
            <th>Valor Glosa da Guia</th>
            <th>Valor Informado do Protocolo</th>
            <th>Valor Processado do Protocolo</th>
            <th>Valor Liberado do Protocolo</th>
            <th>Valor Glosa do Protocolo</th>
            <th>Valor Informado Geral</th>
            <th>Valor Processado Geral</th>
            <th>Valor Liberado Geral</th>
            <th>Valor Glosa Geral</th>
        </tr>
    </thead>
    <tbody>
        @php
            function convertNumber($number)
            {
                $number = str_replace('.', '', $number);
                $number = str_replace(',', '.', $number);
                return number_format((float) $number, 2, '.', '');
            }
        @endphp
        @foreach ($beneficiarios as $beneficiario)
            @foreach ($beneficiario['saude'] as $saude)
                <tr>
                    <td>{{ $protocolo['1 - Registro ANS'] }}</td>
                    <td>{{ $protocolo['3 - Nome da Operadora'] }}</td>
                    <td>{{ $protocolo['6 - Código na Operadora'] }}</td>
                    <td>{{ $protocolo['7 - Nome do Contratado'] }}</td>
                    <td>{{ $protocolo['9 - Número do Lote'] }}</td>
                    <td>{{ $protocolo['10 - Nº do Protocolo (Processo)'] }}</td>
                    <td>{{ $protocolo['11- Data do Protocolo'] }}</td>
                    <td>{{ $protocolo['12 - Código da Glosa do Protocolo'] }}</td>
                    <td>{{ $beneficiario['13 - Número da Guia no Prestador'] }}</td>
                    <td>{{ $beneficiario['14 - Número da Guia Atribuido pela Operadora'] }}</td>
                    <td>{{ $beneficiario['15 - Senha'] }}</td>
                    <td>{{ $beneficiario['16 - Nome do Beneficiário'] }}</td>
                    <td>{{ $beneficiario['17 - Número da Carteira'] }}</td>
                    <td>{{ $beneficiario['18 - Data Início do Faturamento'] }}</td>
                    <td>{{ $beneficiario['19 - Hora Início do Faturamento'] }}</td>
                    <td>{{ $beneficiario['20 - Data Fim do Faturamento'] }}</td>
                    <td>{{ $beneficiario['22 - Código da Glosa da Guia'] }}</td>
                    <td>{{ $saude['23 - Data de realização'] }}</td>
                    <td>{{ $saude['24 - Tabela'] }}</td>
                    <td>{{ $saude['25 - Código Procedimento'] }}</td>
                    <td>{{ $saude['26 - Descrição'] }}</td>
                    <td>{{ $saude['27 - Grau de Participação'] }}</td>
                    <td>{{ convertNumber($saude['28 - Valor Informado']) }}</td>
                    <td>{{ $saude['29 - Quant. Executada'] }}</td>
                    <td>{{ convertNumber($saude['30 - Valor Processado']) }}</td>
                    <td>{{ convertNumber($saude['31 - Valor Liberado']) }}</td>
                    <td>{{ convertNumber($saude['32 - Valor Glosa']) }}</td>
                    <td>{{ $saude['33 - Código da Glosa'] }}</td>
                    <td>{{ convertNumber($beneficiario['34 - Valor Informado da Guia (R$)']) }}</td>
                    <td>{{ convertNumber($beneficiario['35 - Valor Processado da Guia (R$)']) }}</td>
                    <td>{{ convertNumber($beneficiario['36 - Valor Liberado da Guia (R$)']) }}</td>
                    <td>{{ convertNumber($beneficiario['37 - Valor Glosa da Guia (R$)']) }}</td>
                    <td>{{ convertNumber($protocolo['38 - Valor Informado do Protocolo (R$)']) }}</td>
                    <td>{{ convertNumber($protocolo['39 - Valor Processado do Protocolo (R$)']) }}</td>
                    <td>{{ convertNumber($protocolo['40 - Valor Liberado do Protocolo (R$)']) }}</td>
                    <td>{{ convertNumber($protocolo['41 - Valor Glosa do Protocolo (R$)']) }}</td>
                    <td>{{ convertNumber($protocolo['42 - Valor Informado Geral (R$)']) }}</td>
                    <td>{{ convertNumber($protocolo['43 - Valor Processado Geral (R$)']) }}</td>
                    <td>{{ convertNumber($protocolo['44 - Valor Liberado Geral (R$)']) }}</td>
                    <td>{{ convertNumber($protocolo['45 - Valor Glosa Geral (R$)']) }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
