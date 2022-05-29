<?php

namespace Modules\Report\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductSalesExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function products($products)
    {
        $this->products = $products;

        return $this;
    }

    public function company($company)
    {
        $this->company = $company;

        return $this;
    }

    public function filters($filters)
    {
        $this->filters = $filters;

        return $this;
    }

    public function view(): View
    {
        return view('report::products.report_excel', [
            'products' => $this->products,
            'company' => $this->company,
            'filters' => $this->filters
        ]);
    }
}
