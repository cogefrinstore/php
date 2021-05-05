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

<section class="product-discounts">
  {if $quantityDiscounts}
  <button class="disc-btn btn btn-primary">{l s='Discounts' mod='wkdisplaydiscount'}
    <span class="material-icons">keyboard_arrow_down</span>
  </button>
    {block name='product_discount_table'}
      <div class="wk-discount-table-container table-responsive">
        <table class="wk-table-product-discounts discount-table" style="display: none;">
          <thead>
            <tr>
              <th>{l s='Quantity' mod='wkdisplaydiscount'}</th>
              {if isset($config_unit_price) && $config_unit_price}
              <th>{l s='Unit Price' mod='wkdisplaydiscount'}</th>
              {/if}
              <th>{l s='Pieces' mod='wkdisplaydiscount'}</th>
              <th>{l s='Pieces price' mod='wkdisplaydiscount'}</th>
              <th>{$configuration.quantity_discount.label}</th>
              <th>{l s='You Save' mod='wkdisplaydiscount'}</th>
            </tr>
          </thead>
          <tbody>
          {foreach from=$quantityDiscounts item='quantity_discount' name='quantity_discounts'}
            <tr data-discount-type="{$quantity_discount.reduction_type}" data-discount="{$quantity_discount.real_value}" data-discount-quantity="{$quantity_discount.quantity}">
                <td>{$quantity_discount.quantity}</td>
                <td>{$quantity_discount.discount}</td>
                <td>{l s='Up to %s' sprintf=[$quantity_discount.save] mod='wkdisplaydiscount'}</td>
                {if isset($config_unit_price) && $config_unit_price}
                <td>{$quantity_discount.new_unit_price}</td>
                {/if}
            </tr>
            <tr data-discount-type="{$quantity_discount.reduction_type}" data-discount="{$quantity_discount.real_value}" data-discount-quantity="{$quantity_discount.quantity}">
                <td>{$quantity_discount.quantity}</td>
                <td>{$quantity_discount.discount}</td>
                <td>{l s='Up to %s' sprintf=[$quantity_discount.save] mod='wkdisplaydiscount'}</td>
                {if isset($config_unit_price) && $config_unit_price}
                <td>{$quantity_discount.new_unit_price}</td>
                {/if}
            </tr>
            <tr data-discount-type="{$quantity_discount.reduction_type}" data-discount="{$quantity_discount.real_value}" data-discount-quantity="{$quantity_discount.quantity}">
                <td>{$quantity_discount.quantity}</td>
                <td>{$quantity_discount.discount}</td>
                <td>{l s='Up to %s' sprintf=[$quantity_discount.save] mod='wkdisplaydiscount'}</td>
                {if isset($config_unit_price) && $config_unit_price}
                <td>{$quantity_discount.new_unit_price}</td>
                {/if}
            </tr>
          {/foreach}
          </tbody>
        </table>
      </div>
    {/block}
  {/if}
</section>
