{if $visible == 1}
    <div class="upsellItemContainer">
        <img class="js-qv-product-cover img-fluid" src="{$imageURL}" alt="product image">
        {$productName}
        <form action="{url entity="module" name="upsellbundle" controller="addToCart"}" method="post" name="checkbox-form">
            <input type="hidden" name="token" value="{$static_token}">
            <input type="hidden" value="{$productId}" name="id_product">
            <input type="hidden" value="{$productDefaultAttributeId}" name="id_product_attribute">
            <button type="submit" for="checkbox-form" >Add to cart</button>
        </form>
    </div>
{/if}