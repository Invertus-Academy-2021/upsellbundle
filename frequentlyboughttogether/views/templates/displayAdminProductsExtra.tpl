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
                     Image
                  </th>
                  <th scope="col">
                     <div class="ps-sortable-column" data-sort-col-name="name" data-sort-prefix="">
                        <span role="columnheader">Name</span>
                        <span role="button" class="ps-sort" aria-label="Sort by"></span>
                     </div>
                  </th>
                  <th scope="col" class="text-center" style="width: 9%">
                     <div class="ps-sortable-column" data-sort-col-name="price" data-sort-prefix="">
                        <span role="columnheader">Price (tax excl.)</span>
                        <span role="button" class="ps-sort" aria-label="Sort by"></span>
                     </div>
                  </th>
                  <th scope="col" class="text-center" style="width: 9%">
                     Price (tax incl.)
                  </th>
            </thead>

        <tbody>
            {foreach from=$products item="product"}
                <tr>
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