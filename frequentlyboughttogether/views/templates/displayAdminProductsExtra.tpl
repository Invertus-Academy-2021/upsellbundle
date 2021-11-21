<div class="row">
   <div class="col-md-12">
      <div class="table-responsive">
         <table class="table product mt-3" redirecturl="/prestashop/admin/index.php/sell/catalog/products/0/20/id_product/desc?_token=Jy3-ZesF9fe2G4Sjm8QOjCgdAUBnOv90ZWHwg-eUpPA">
            <thead class="with-filters">
               <tr class="column-headers">
                  <th scope="col" style="width: 2rem"></th>
                  <th scope="col" style="width: 5rem">
                     <div class="ps-sortable-column" data-sort-col-name="id_product" data-sort-prefix="" data-sort-is-current="true" data-sort-direction="desc">
                        <span role="columnheader">ID</span>
                        <span role="button" class="ps-sort" aria-label="Sort by"></span>
                     </div>
                  </th>
                  <th scope="col">
                     <div class="ps-sortable-column" data-sort-col-name="image" data-sort-prefix="">
                        <span role="columnheader">Image</span>
                        <span role="button" class="ps-sort" aria-label="Sort by"></span>
                     </div>
                  </th>
                  <th scope="col">
                     <div class="ps-sortable-column" data-sort-col-name="name" data-sort-prefix="">
                        <span role="columnheader">Name</span>
                        <span role="button" class="ps-sort" aria-label="Sort by"></span>
                     </div>
                  </th>
            </thead>

        <tbody>
            {foreach from=$products item="product"}
                <tr>
                  <td class="checkbox-column form-group">
                     <div class="md-checkbox md-checkbox-inline">
                        <label>
                              <input type="checkbox" id="bulk_action_selected_products-{$product.id_product}" name="bulk_action_selected_products[]" value={$product.id_product}{if $product.id_product|in_array:$checkedProducts} checked {/if}>
                              <i class="md-checkbox-control"></i>
                        </label>
                     </div>
                  </td>
                    <td>
                    {$product.id_product}
                    </td>
                    <td>
                    <a href="/prestashop/admin/index.php/sell/catalog/products/{$product.id_product}"><img src="/prestashop/img/tmp/product_mini_{$product.id_product}.jpg" alt="" class="imgm img-thumbnail"></a>
                    </td>
                    <td>
                    {$product.name}
                    </td>
                </tr>
            {/foreach}
        </tbody>
        </table>
      </div>
   </div>
</div>