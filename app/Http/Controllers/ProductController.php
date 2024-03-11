<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
// export csv import Storage
use Illuminate\Support\Facades\Storage;
use PDF;

class ProductController extends Controller
{
    public function index()
    {
        $productList = Product::paginate(10);
        $data = [
            'status' => 200,
            'products' => $productList
        ];
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $product = Product::find($id);
        $data = [
            'status' => 200,
            'product' => $product
        ];
        return response()->json($data, 200);
    }

    public function create(Request $request)
    {
        $product = Product::create($request->all());
        $data = [
            'status' => 200,
            'message' => 'Add Product Success',
            'product' => $product
        ];
        return response()->json($data, 200);
    }

    public function edit(Request $request, $id)
    {
        $product = Product::find($id);
        $product->update($request->all());

        $data = [
            'status' => 200,
            'message' => 'Add Update Success',
            'product' => $product
        ];
        return response()->json($data, 200);
    }

    public function delete($id)
    {
        $product = Product::find($id)->delete();
        $data = [
            'status' => 200,
            'message' => 'Delete Product Success',
            'product' => $product
        ];
        return response()->json($data, 200);
    }

    public function exportToCSV()
    {
        $products = Product::all();
        // return response()->json($products, 200);
        $csvFileName = 'products.csv';
        // Check if the file exists
        if (!Storage::exists($csvFileName)) {
            // Create a new empty CSV file with the header row
            $csvHeader = [
                'ID',
                'Name',
                'Quantity',
                'Buying Price',
                'Selling Price',
                'Description',
                'Image URL',
                'Created At',
                'Updated At',
            ];

            Storage::put($csvFileName, implode(',', $csvHeader));
        }

        // Append the CSV data to the file
        foreach ($products as $product) {
            $csvData = [
                $product->id,
                $product->name,
                $product->quantity,
                $product->buyingPrice,
                $product->sellingPrice,
                $product->description,
                $product->image_url,
                $product->created_at,
                $product->updated_at,
            ];

            Storage::append($csvFileName, implode(',', $csvData));
            $csvFilePath = Storage::path($csvFileName);

            return response()->download($csvFilePath)->deleteFileAfterSend(true);

        }
    }

    // Start Generate PDF==========================================================================
    public function generatePDF()
    {
        // Get products from the database
        $products = Product::limit(100)->get();
        
        // Generate the PDF view
        $pdf = PDF::loadView('PDF.Products', compact('products'));

        // Customize the PDF settings (optional)
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isFontSubsettingEnabled' => true,
        ]);
        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed' => TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );

        // Save or display the PDF (as needed)
        return $pdf->stream('product_list.pdf');
    }
    // End Generate PDF==========================================================================

}
