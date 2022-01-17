<?php

namespace App;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function product_category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function get_link()
    {
        if ($this->page_id != NULL) {
            $link = route('page.show', $this->page->slug);
        } elseif ($this->product_category_id != NULL) {
            if ($this->product_category != NULL) {
                $link = route('front.shopping.shopping_category', $this->product_category->slug);
            } else {
                $link = '@javascript:void(0)';
            }
        } else {
            $link = $this->link;
        }

        return $link;
    }

    public function get_tipe_menu()
    {
        if ($this->page_id != NULL) {
            $tipe = 'Custom Halaman';
        } elseif ($this->product_category_id != NULL) {
            $tipe = 'Halaman Kategori Produk';
        } else {
            $tipe = 'Custom Link';
        }

        return $tipe;
    }
}
