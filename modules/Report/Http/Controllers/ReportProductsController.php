<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentItem;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use App\Models\Tenant\PurchaseItem;
use App\Models\Tenant\SaleNoteItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Report\Exports\ProductSalesExport;

class ReportProductsController extends Controller
{
    public function index()
    {
        return view('report::products.index')->with('products', null);
    }

    public function searchProducts(Request $request)
    {
        $f1 = $request->input('date_start');
        $f2 = $request->input('date_end');
        $name = $request->input('product');

        $products = [];

        $validator = Validator::make($request->all(), [
            'date_start' => 'required',
            'date_end' => 'required',
            'product' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $products = $this->getItems($name, $f1, $f2);

        return view('report::products.index')->with('products', $products);
    }

    public function exportProducts($type, $na, $fs, $fe)
    {
        $f1 = $fs;
        $f2 = $fe;
        $name = $na;
        if ($type == 'pdf') {
            return $this->pdf($name, $f1, $f2);
        } else {
            return $this->excel($name, $f1, $f2);
        }
    }

    public function pdf($name, $f1, $f2)
    {
        $products = $this->getItems($name, $f1, $f2);
        $company = Company::first();

        $params = [
            'name' => $name,
            'date_start' => $f1,
            'date_end' => $f2
        ];

        $pdf = PDF::loadView('report::products.report_pdf', compact("products", "company", "params"));

        $filename = 'Reporte_Productos_Ventas_' . date('YmdHis');

        return $pdf->download($filename . '.pdf');
    }

    public function excel($name, $f1, $f2)
    {
        $company = Company::first();
        $products = $this->getItems($name, $f1, $f2);

        $filters = [
            'name' => $name,
            'date_start' => $f1,
            'date_end' => $f2
        ];

        $SaleNoteExport = new ProductSalesExport();
        $SaleNoteExport
            ->products($products)
            ->company($company)
            ->filters($filters);

        return $SaleNoteExport->download('Reporte_Productos_Ventas_' . date('YmdHis') . '.xlsx');
    }

    public function getItems($name, $f1, $f2)
    {
        $array = Item::select('id', 'name', 'stock')
            ->where('name', 'like', '%' . $name . '%')
            ->get();

        $products = [];

        foreach ($array as $k => $row) {
            $products[$k] = array(
                'name' => $row->name,
                'stock' => $row->stock,
                'document_sale' => $this->getDocument($row->id, $f1, $f2),
                'document_purchase' => $this->getPurchase($row->id, $f1, $f2)
            );
        }

        return $products;
    }

    public function getDocument($id, $f1, $f2)
    {

        $array_Sales = DocumentItem::join('documents', 'document_id', 'documents.id')
            ->join('cat_document_types', 'document_type_id', 'cat_document_types.id')
            ->select(
                'cat_document_types.description',
                'documents.series',
                'documents.number',
                'document_items.quantity',
                'document_items.total',
            )
            ->where('item_id', $id)
            ->whereBetween('date_of_issue', [$f1, $f2])
            ->get();

        $sales = [];

        $k = 0;

        if (count($array_Sales) > 0) {
            foreach ($array_Sales as $row) {
                $sales[$k] = array(
                    'description' => $row->description,
                    'series' => $row->series,
                    'number' => $row->number,
                    'quantity' => $row->quantity,
                    'total' => $row->total,
                );
                $k++;
            }
        }


        $array_notes = SaleNoteItem::join('sale_notes', 'sale_note_id', 'sale_notes.id')
            ->select(
                DB::raw('(SELECT description FROM cat_document_types WHERE id="80") AS description'),
                'sale_notes.series',
                'sale_notes.number',
                'sale_note_items.quantity',
                'sale_note_items.total',
            )
            ->where('item_id', $id)
            ->whereBetween('date_of_issue', [$f1, $f2])
            ->get();
        if (count($array_notes) > 0) {
            foreach ($array_notes as $row) {
                $sales[$k] = array(
                    'description' => $row->description,
                    'series' => $row->series,
                    'number' => $row->number,
                    'quantity' => $row->quantity,
                    'total' => $row->total,
                );

                $k++;
            }
        }

        return $sales;
    }

    public function getPurchase($id, $f1, $f2)
    {

        $array = PurchaseItem::join('purchases', 'purchase_id', 'purchases.id')
            ->join('cat_document_types', 'document_type_id', 'cat_document_types.id')
            ->select(
                'cat_document_types.description',
                'purchases.series',
                'purchases.number',
                'purchase_items.quantity',
                'purchase_items.total',
            )
            ->where('item_id', $id)
            ->whereBetween('date_of_issue', [$f1, $f2])
            ->get();

        $purchases = [];

        $k = 0;
        if (count($array) > 0) {
            foreach ($array as $row) {
                $purchases[$k] = array(
                    'description' => $row->description,
                    'series' => $row->series,
                    'number' => $row->number,
                    'quantity' => $row->quantity,
                    'total' => $row->total,
                );
                $k++;
            }
        }

        return $purchases;
    }
}
