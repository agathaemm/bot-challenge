<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class PdfExport implements FromView
{
    public function __construct(public $protocolo, public $beneficiarios)
    {
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        return view('exports.demonstrative', [
            'protocolo' => $this->protocolo,
            'beneficiarios' => $this->beneficiarios
        ]);
    }
}
