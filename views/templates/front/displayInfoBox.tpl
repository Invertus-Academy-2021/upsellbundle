{if $visible == 1}
    <div class="upsellItemContainer">
        <div class="card text-center">
            <img class="card-img-top" src="{$imageURL}" alt="product image">
            <div class="card-body">
                <h5 class="card-title">{$productName}</h5>
                <p class="card-text">Specialus pasiūlymas - tik šiame krepšelyje gaukite šią prekę 20% pigiau.
                    <div class="product-discount">
                        <span class="regular-price" itemprop="price" content="{$productPrice}
                        ">{Tools::displayPrice(round($productPrice, 2))}</span>
                    </div>
                    <div class="current-price">
                        <span class="price">{Tools::displayPrice(round($discountedProductPrice, 2))}</span>
                    </div>
                </p>
                <form action="{url entity="module" name="upsellbundle" controller="addToCart"}" method="post"
                      name="checkbox-form">
                    <input type="hidden" name="token" value="{$static_token}">
                    <input type="hidden" value="{$productId}" name="id_product">
                    <input type="hidden" value="{$productDefaultAttributeId}" name="id_product_attribute">
                    <button type="submit" for="checkbox-form" class="btn btn-primary">Į krepšelį</button>
                </form>
            </div>
        </div>
    </div>
{/if}