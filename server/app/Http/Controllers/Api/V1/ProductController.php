<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product as ProductResource;
use App\Models\Product;
use App\Models\StockDivision;
use CloudCreativity\LaravelJsonApi\Http\Controllers\JsonApiController;
use Illuminate\Support\Facades\Storage;
use Response;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index ()
    {
        return ProductResource::collection(Product::all());
    }

    public function show($id)
    {
        return new ProductResource(Product::find($id));
    }

    // returns true if current user has purchased the product, else false
    public function getIsPurchased($id)
    {
        $user = auth('api')->user();
        $product = Product::find($id);

        return Response::JSON($user->hasPurchasedProduct($product));
    }

    // returns true if current user has purchased the product, else false
    public function getIsMine($id)
    {
        $user = auth('api')->user();
        $product = Product::find($id);

        return Response::JSON($product->isOwnedBy($user));
    }

    public function getPlaybackVideoFile($id)
    {
        $product = Product::find($id);
        $filePath = $product->seminarProductMeta->playback_video_file_path;

        // Create temporary download link and redirect
        $adapter = Storage::disk('s3')->getAdapter();
        $client = $adapter->getClient();
        $client->registerStreamWrapper();
        $object = $client->headObject([
            'Bucket' => $adapter->getBucket(),
            'Key' => $filePath,
        ]);
        /*************************************************************************
         * Set headers to allow browser to force a download
         */
        header('Last-Modified: '.$object['LastModified']);
        // header('Etag: '.$object['ETag']); # We are not implementing validation caching here, but we could!
        header('Accept-Ranges: '.$object['AcceptRanges']);
        header('Content-Length: '.$object['ContentLength']);
        header('Content-Type: '.$object['ContentType']);
        header('Content-Disposition: attachment; filename=video.mp4');
        /*************************************************************************
         * Stream file to the browser
         */
        // Open a stream in read-only mode
        if (!($stream = fopen("s3://{$adapter->getBucket()}/{$filePath}", 'r'))) {
            throw new \Exception('Could not open stream for reading file: ['.$s3FileKey.']');
        }

        // Check if the stream has more data to read
        while (!feof($stream)) {
            // Read 1024 bytes from the stream
            echo fread($stream, 1024);
        }

        // Be sure to close the stream resource when you're done with it
        fclose($stream);

    }

    // returns integer of products with stock <= minimum stock
    public function getLowStockCount()
    {
        $count = Product::stockAtMinimumOrLess()->count();
        return Response::JSON($count);
    }

    // returns integer of products with stock <= 0
    public function getOutOfStockCount()
    {
        $count = Product::outOfStock()->count();
        return Response::JSON($count);
    }

    /**
     * @return JsonResponse
     */
    public function getProductStockInRackByStockDivision(Request $request, $productId, $stockDivisionId)
    {
        $stockDivision = StockDivision::with([
            'productStocks' => function($q) {
                $q->with([
                    'rack' => function($q) {
                        $q->select(
                            'racks.id',
                            'racks.warehouse_id',
                            'racks.name'
                        )
                        ->with([
                            'warehouse' => function($q) {
                                $q->select('warehouses.id', 'warehouses.name');
                            },
                            'productStocks' => function($q) {
                                $q->select(
                                    'product_stocks.id',
                                    'product_stocks.product_id',
                                    'product_stocks.rack_id',
                                    'product_stocks.stock_division_id',
                                    'product_stocks.quantity'
                                );
                            }
                        ]);
                    }
                ]);
            }
        ])->find($stockDivisionId);

        $productStocksInStockDiv = $stockDivision->productStocks->filter(function($ps) use ($productId) {
            return $ps->product_id == $productId;
        });
        $racks = $productStocksInStockDiv->pluck('rack')->unique()->sortBy('warehouse.name', SORT_NATURAL)->all();
        $results = [];
        $prevWarehouseName = "";
        $prevRackName = "";

        foreach ($racks as $rack) {
            $productStocksInRack = $rack->productStocks->filter(function ($ps) use ($productId, $stockDivisionId) {
                return ($ps->product_id == $productId) && ($ps->stock_division_id == $stockDivisionId);
            });
            $results[] = [
                'warehouseName' => ($prevWarehouseName != $rack->warehouse->name ? $rack->warehouse->name : ""),
                'rackName' => ($prevRackName != $rack->name ? $rack->name : ""),
                'quantity' => $productStocksInRack->sum('quantity'),
                'rackId' => $rack->id,
                'psId' => $productStocksInRack->pluck('id')->all()
            ];
            $prevWarehouseName = $rack->warehouse->name;
            $prevRackName = $rack->name;
        }

        return $results;
    }
}
