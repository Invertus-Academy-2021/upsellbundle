<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

<div class="fbt-main">
    <div class="fbt-main-margin">
        <div class="fbt-header">
            <p class="fbt-title headings headings-small secondary-font font-weight-bold desc-block-title">
                Frequently bought together
            </p>
        </div>
        <div class="fbt-container">
            <div class="fbt-items-and-price">
                <form action="{url entity="module" name="frequentlyboughttogether" controller="addToCart"}" method="post" name="checkbox-form">
                <div class ="fbt-items">
                        <ul style="list-style: none;">
                        {foreach $checkedProducts as $key=>$checkedProduct}
                        <li>
                            <div class="fbt-item">
                                <div class="fbt-checkbox">
                                    <input  type="checkbox" data-price="{$checkedProduct->price}" id="selected_products-{$key}" name="selected_products[]" value={$checkedProduct->id} >
                                    <i class="md-checkbox-control"></i> 
                                </div>
                                <div class="fbt-image"> 
                                    <a href="/prestashop/admin/index.php/sell/catalog/products/{$key+1}">
                                        <img src="/prestashop/img/tmp/product_mini_{$key+1}.jpg" alt="" class="imgm img-thumbnail">
                                    </a>
                                </div>
                                <div class="fbt-text">
                                    <span>
                                        {$checkedProduct->name} <br>
                                    </span>
                                    <p>
                                        {$checkedProduct->description_short|strip_tags}
                                    </p>
                                </div>
                            </div>
                        </li>
                        {/foreach}
                        </ul>
                </div>
                <div class="fbt-price">
                    <div class="fbt-price-text">
                    </div>
                    <div class="fbt-to-cart"> 
                        <p class="fbt-total-price">Total price: 0 €
                        </p>                       
                        <button type="submit" for="checkbox-form" class="fbt-btn-to-cart btn btn-success"><i class="fas fa-shopping-cart"></i> &nbsp    Add to cart</button>
                    </div>
                    
                </div>
            </form>
            
            </div>
    </div>
</div>