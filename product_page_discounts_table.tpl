{*
/**
* 2010-2020 Webkul.
*
* NOTICE OF LICENSE
*
* All right is reserved,
* Please go through this link for complete license : https://store.webkul.com/license.html
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to https://store.webkul.com/customisation-guidelines/ for more information.
*
* @author Webkul IN <support@webkul.com>
* @copyright 2010-2020 Webkul IN
* @license https://store.webkul.com/license.html
*/
*}

<section class="product-discounts wk-product-discounts" style="overflow-x:auto;">
  {if $quantityDiscounts}
    <p class="h6 product-discounts-title">{l s='Volume discounts' mod='wkdisplaydiscount'}</p>
    {block name='product_discount_table'}
      <table class="table-product-discounts">
        <thead>
          <tr class="table-product-discounts-tr">
            <th class="table-product-discounts-th">{l s='Quantity' mod='wkdisplaydiscount'}</th>
            {if isset($config_unit_price) && $config_unit_price}
            <th class="table-product-discounts-th">{l s='Unit Price' mod='wkdisplaydiscount'}</th>
            {/if}
            <th class="table-product-discounts-th">{l s='Pieces' mod='wkdisplaydiscount'}</th> 
            <th class="table-product-discounts-th">{l s='Pieces price' mod='wkdisplaydiscount'}</th> 

            <th class="table-product-discounts-th">{l s='You Save' mod='wkdisplaydiscount'}</th>
          </tr>
        </thead>
        <tbody>
        {foreach from=$quantityDiscounts item='quantity_discount' name='quantity_discounts'}
          <tr data-discount-type="{$quantity_discount.reduction_type}" data-discount="{$quantity_discount.real_value}" data-discount-quantity="{$quantity_discount.quantity}">
              <td class="table-product-discounts-td">{$quantity_discount.quantity}</td>
              {if isset($config_unit_price) && $config_unit_price}
              <!-- Applico lo scorporo iva -->
              {assign var="cento" value="100" nocache}
              {assign "cento" "100"} {* short-hand *}
              <td class="table-product-discounts-td">{round(($product.price_without_reduction - $quantity_discount.real_value),2)} €</td>
              {/if}
              {if isset($product.features)}
                <td class="table-product-discounts-td">
                {foreach from=$product.features item=feature}
                
                {$feature.value * $quantity_discount.quantity}
                
                {/foreach}
                </td>
              {/if}
              {if isset($product.features)}
                <td class="table-product-discounts-td">
                {foreach from=$product.features item=feature}
                
                {round((($product.price_without_reduction - $quantity_discount.real_value) / $feature.value),2)} &euro;
                
                {/foreach}
                </td>
              {/if}
              
              <td class="table-product-discounts-td">{l s='Up to %s' sprintf=[$quantity_discount.save] mod='wkdisplaydiscount'}</td>
          </tr>
        {/foreach}
        </tbody>
      </table>
    {/block}
  {/if}
</section>
