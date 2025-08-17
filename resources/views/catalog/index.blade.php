@extends('layouts.site')

@section('title', $title ?? __('catalog.title'))
@section('meta_description', $meta_description ?? __('catalog.description'))

@section('content')
    <div class="container-fluid" id="products-container" style="border-left:4px solid #252529;">
        <div class="row" id="itmesvalue">
            @foreach($products as $product)
                <a class="filter-type col-md-3 product text-center" href="{{ route('product.show', [app()->getLocale(), $product->slug]) }}">
                    <div class="gradient"></div>
                    <img loading="lazy" data-src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    <h3>{{ $product->name }}</h3>
                    <p>{{ Str::limit($product->short_description, 120) }}</p>
                </a>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection
