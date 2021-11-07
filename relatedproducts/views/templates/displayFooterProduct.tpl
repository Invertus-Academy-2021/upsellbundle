<section class="featured-products clearfix relatedproducts">
  <h2 class="h2 products-section-title text-uppercase">
    Related products
  </h2>
  {include file="catalog/_partials/productlist.tpl" products=$products cssClass="row"}
  <a class="all-product-link float-xs-left float-md-right h4" href="{$allProductsLink}">
    {l s='All products' d='Shop.Theme.Catalog'}<i class="material-icons">&#xE315;</i>
  </a>
</section>