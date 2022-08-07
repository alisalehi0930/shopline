<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Modules\Product\Http\Requests\ProductRequest;
use Modules\Product\Repositories\ProductRepo;
use Modules\Product\Services\ProductService;
use Modules\Share\Http\Controllers\Controller;
use Modules\Share\Services\ShareService;

class ProductController extends Controller
{
    public ProductService $service;
    public ProductRepo $repo;

    public function __construct(ProductService $productService, ProductRepo $productRepo)
    {
        $this->service = $productService;
        $this->repo = $productRepo;
    }

    /**
     * Show index page of products.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('Product::index');
    }

    /**
     * Show create product page.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('Product::create');
    }

    /**
     * Store product.
     *
     * @param ProductRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(ProductRequest $request)
    {
        ShareService::uploadMediaWithAddInRequest($request, 'first_media', 'first_media_id');
        ShareService::uploadMediaWithAddInRequest($request, 'second_media', 'second_media_id');

        $product = $this->service->store($request);

        $this->service->attachCategoreisToProduct($request->categories, $product);
        $this->service->attachGalleriesToProduct($request->galleries, $product);

        if ($request->attributes) {
            $this->service->attachAttributesToProduct($request->attributes, $product);
        }
        if ($request->tags) {
            $this->service->attachTagsToProduct($request->tags, $product);
        }

        return to_route('products.index');
    }

    /**
     * Find product by id with show edit product page.
     *
     * @param  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $product = $this->repo->findById($id);

        return view('Product::edit', compact(['product']));
    }
}