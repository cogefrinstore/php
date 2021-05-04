<?php
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
*  @author    Webkul IN <support@webkul.com>
*  @copyright 2010-2020 Webkul IN
*  @license   https://store.webkul.com/license.html
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;

class WkDisplayDiscount extends Module
{
    public $discountDisplaySelectedPages = array();

    const WK_PRODUCT_DISCOUNT_TABLE_DEFAULT = 1;
    const WK_PRODUCT_DISCOUNT_TABLE_AFTER_THUMBS = 2;
    const WK_PRODUCT_DISCOUNT_TABLE_ADDITIONAL_INFO = 3;
    const WK_PRODUCT_DISCOUNT_TABLE_PRICE_BLOCK = 4;

    const WK_PRODUCT_DISCOUNT_TABLE_ALL_PAGES = 1;
    const WK_PRODUCT_DISCOUNT_TABLE_SELECTED_PAGES = 2;

    const WK_PRODUCT_DISCOUNT_TABLE_HOME_PAGE = 7;
    const WK_PRODUCT_DISCOUNT_TABLE_CATEGORY_PAGE = 8;
    const WK_PRODUCT_DISCOUNT_TABLE_BEST_SALES = 9;
    const WK_PRODUCT_DISCOUNT_TABLE_PRICES_DROP = 10;
    const WK_PRODUCT_DISCOUNT_TABLE_NEW_PRODUCTS = 11;
    const WK_PRODUCT_DISCOUNT_TABLE_SEARCH = 12;
    const WK_PRODUCT_DISCOUNT_TABLE_MANUFACTURER = 13;
    const WK_PRODUCT_DISCOUNT_TABLE_SUPLIER = 14;

    public function __construct()
    {
        $this->name = 'wkdisplaydiscount';
        $this->tab = 'front_office_features';
        $this->version = '4.0.0';
        $this->module_key = '2f2ba997d23187eb0750da028358e64d';
        $this->author = 'Webkul';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array(
            'min' => '1.7',
            'max' => _PS_VERSION_
        );
        $this->bootstrap = true;
        $this->discountDisplaySelectedPages = array(
            'index' => array(
                'page_id' => self::WK_PRODUCT_DISCOUNT_TABLE_HOME_PAGE,
                'controller' => 'index',
                'on' => Configuration::get(
                    'WK_DISPLAY_DISCOUNT_SELECTED_LISTING_PAGES_'.self::WK_PRODUCT_DISCOUNT_TABLE_HOME_PAGE,
                    null
                )
            ),
            'category' => array(
                'page_id' => self::WK_PRODUCT_DISCOUNT_TABLE_CATEGORY_PAGE,
                'controller' => 'category',
                'on' => Configuration::get(
                    'WK_DISPLAY_DISCOUNT_SELECTED_LISTING_PAGES_'.self::WK_PRODUCT_DISCOUNT_TABLE_CATEGORY_PAGE,
                    null
                )
            ),
            'bestsales' => array(
                'page_id' => self::WK_PRODUCT_DISCOUNT_TABLE_BEST_SALES,
                'controller' => 'bestsales',
                'on' => Configuration::get(
                    'WK_DISPLAY_DISCOUNT_SELECTED_LISTING_PAGES_'.self::WK_PRODUCT_DISCOUNT_TABLE_BEST_SALES,
                    null
                )
            ),
            'pricesdrop' => array(
                'page_id' => self::WK_PRODUCT_DISCOUNT_TABLE_PRICES_DROP,
                'controller' => 'pricesdrop',
                'on' => Configuration::get(
                    'WK_DISPLAY_DISCOUNT_SELECTED_LISTING_PAGES_'.self::WK_PRODUCT_DISCOUNT_TABLE_PRICES_DROP,
                    null
                )
            ),
            'newproducts' => array(
                'page_id' => self::WK_PRODUCT_DISCOUNT_TABLE_NEW_PRODUCTS,
                'controller' => 'newproducts',
                'on' => Configuration::get(
                    'WK_DISPLAY_DISCOUNT_SELECTED_LISTING_PAGES_'.self::WK_PRODUCT_DISCOUNT_TABLE_NEW_PRODUCTS,
                    null
                )
            ),
            'search' => array(
                'page_id' => self::WK_PRODUCT_DISCOUNT_TABLE_SEARCH,
                'controller' => 'search',
                'on' => Configuration::get(
                    'WK_DISPLAY_DISCOUNT_SELECTED_LISTING_PAGES_'.self::WK_PRODUCT_DISCOUNT_TABLE_SEARCH,
                    null
                )
            ),
            'manufacturer' => array(
                'page_id' => self::WK_PRODUCT_DISCOUNT_TABLE_MANUFACTURER,
                'controller' => 'manufacturer',
                'on' => Configuration::get(
                    'WK_DISPLAY_DISCOUNT_SELECTED_LISTING_PAGES_'.self::WK_PRODUCT_DISCOUNT_TABLE_MANUFACTURER,
                    null
                )
            ),
            'supplier' => array(
                'page_id' => self::WK_PRODUCT_DISCOUNT_TABLE_SUPLIER,
                'controller' => 'supplier',
                'on' => Configuration::get(
                    'WK_DISPLAY_DISCOUNT_SELECTED_LISTING_PAGES_'.self::WK_PRODUCT_DISCOUNT_TABLE_SUPLIER,
                    null
                )
            ),
        );

        parent::__construct();

        $this->displayName = $this->l('Quantity Discount Block');
        $this->description = $this->l('Module displays available volume discounts of the product on product page and product listing.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall ?');
    }

    public function getContent()
    {
        // Handles Configuration Sumbit
        if (Tools::isSubmit('submitWkDisplayDiscountModule')) {
            $this->postProcess();
            $this->context->controller->confirmations = $this->l('General configurations saved successfully.');
        }
        return $this->renderForm();
    }

    public function hookDisplayWkAfterProductThumbs($params)
    {
        // Displaying discount table below thumbnail of product in product page
        if (!Tools::getValue('ajax')
            && Configuration::get('WK_DISPLAY_DISCOUNT_POSITION', null) == self::WK_PRODUCT_DISCOUNT_TABLE_AFTER_THUMBS
            && Configuration::get('WK_DISPLAY_DISCOUNT_ON_PRODUCT_PAGE', null)
        ) {
            return $this->context->smarty->fetch(
                $this->local_path.'views/templates/hook/_partials/product_page_discounts_table.tpl'
            );
        }
    }

    public function hookDisplayProductAdditionalInfo($params)
    {

        // Displaying discount table in additional info in product page
        if (Configuration::get('WK_DISPLAY_DISCOUNT_POSITION', null) == self::WK_PRODUCT_DISCOUNT_TABLE_ADDITIONAL_INFO
            && Configuration::get('WK_DISPLAY_DISCOUNT_ON_PRODUCT_PAGE', null)
        ) {
            return $this->context->smarty->fetch(
                $this->local_path.'views/templates/hook/_partials/product_page_discounts_table.tpl'
            );
        }
    }

    public function hookDisplayProductPriceBlock($params)
    {
        // Displaying Discount table in price block
        // For product controller
        if ("product" == Tools::getValue("controller")) {
            if ($params['type'] == 'after_price'
                && Configuration::get(
                    'WK_DISPLAY_DISCOUNT_POSITION',
                    null
                ) == self::WK_PRODUCT_DISCOUNT_TABLE_PRICE_BLOCK
                && Configuration::get('WK_DISPLAY_DISCOUNT_ON_PRODUCT_PAGE', null)
            ) {
                return $this->context->smarty->fetch(
                    $this->local_path.'views/templates/hook/_partials/product_page_discounts_table.tpl'
                );
            }
        } elseif (array_key_exists(
            Tools::getValue("controller"),
            $this->discountDisplaySelectedPages
        )) {
            if (Configuration::get('WK_DISPLAY_DISCOUNT_MOBILE_VIEW')
                || $this->context->getDevice() == Context::DEVICE_COMPUTER
            ) {
                $context = $this->context;
                $product = new Product((int) $params['product']['id']);
                $tax = (float) $product->getTaxesRate(new Address((int) $context->cart->{
                    Configuration::get('PS_TAX_ADDRESS_TYPE')
                }));
                $specific_prices = SpecificPrice::getQuantityDiscounts(
                    $product->id,
                    (int) $context->shop->id,
                    (int) $context->currency->id,
                    (int) $context->country->id,
                    (int) $context->customer->id_default_group,
                    (int) $params['product']['id_product_attribute'],
                    false,
                    (int) $this->context->customer->id
                );
                $product_price = $product->getPrice(
                    Product::$_taxCalculationMethod == PS_TAX_INC,
                    (int) $params['product']['id_product_attribute']
                );

                // Adding the reduction_with_tax, real_value keys in price rules
                $quantity_discounts = $this->formatQuantityDiscounts(
                    $specific_prices,
                    $product_price,
                    (float) $tax,
                    $product->ecotax
                );
                // Adding unit price key in rules
                foreach ($quantity_discounts as $key => $quantity_discount) {
                    $quantity_discounts[$key]['new_unit_price'] = Tools::displayPrice(Product::getPriceStatic(
                        $product->id,
                        true,
                        null,
                        6,
                        null,
                        false,
                        true,
                        $quantity_discount['quantity']
                    ));
                }

                $this->context->smarty->assign(array(
                    'quantityDiscounts' => $quantity_discounts,
                ));

                if ($params['type'] == 'weight') {
                    if (Configuration::get(
                        'WK_DISPLAY_DISCOUNT_LISTING_PAGES_TYPE',
                        null
                    ) == self::WK_PRODUCT_DISCOUNT_TABLE_ALL_PAGES
                        || in_array(
                            $this->discountDisplaySelectedPages[Tools::getValue("controller")]['page_id'],
                            Tools::jsonDecode(Configuration::get('WK_DISPLAY_DISCOUNT_SELECTED_LISTING_PAGES'))
                        )
                    ) {
                        $this->context->smarty->assign(
                            array(
                                'config_unit_price' => Configuration::get(
                                    'WK_DISPLAY_UNIT_PRICE_ON_PRODUCT_LISTING',
                                    null
                                ),
                            )
                        );
                        return $this->context->smarty->fetch(
                            $this->local_path.'views/templates/hook/_partials/product_discount_table.tpl'
                        );
                    }
                }
            }
        }
    }

    public function hookFilterProductContent($params)
    {
        $product = $params["object"];
        $id_product = $product['id_product'];
        $quantityDiscounts = [];
        if (!empty($product["quantity_discounts"])
            && Configuration::get('WK_DISPLAY_DISCOUNT_ON_PRODUCT_PAGE', null)
        ) {
            foreach ($params["object"]["quantity_discounts"] as $value) {
                array_push($quantityDiscounts, $value);
            }

            // Adding unit price key in rules
            foreach ($quantityDiscounts as &$value) {
                $value['new_unit_price'] = Tools::displayPrice(
                    Product::getPriceStatic(
                        $id_product,
                        true,
                        null,
                        6,
                        null,
                        false,
                        true,
                        $value['quantity']
                    )
                );
            }
        }
        Media::addJsDef(
            array(
                'is_default_pos' => (Configuration::get(
                    'WK_DISPLAY_DISCOUNT_POSITION',
                    null
                ) == self::WK_PRODUCT_DISCOUNT_TABLE_DEFAULT) && Configuration::get(
                    'WK_DISPLAY_DISCOUNT_ON_PRODUCT_PAGE',
                    null
                ) ,
                'txt_unit_price' => $this->l('Unit price'),
                'allUnitPrice' => array_column($quantityDiscounts, 'new_unit_price'),
                'config_unit_price' => Configuration::get('WK_DISPLAY_DISCOUNT_PRICE_ON_PRODUCT_PAGE', null)
            )
        );
        $this->context->smarty->assign(array(
            'quantityDiscounts' => $quantityDiscounts,
            'config_unit_price' => Configuration::get('WK_DISPLAY_DISCOUNT_PRICE_ON_PRODUCT_PAGE', null)
        ));
        return $params;
    }

    public function hookDisplayOverrideTemplate($params)
    {
        // override product template to override product_discounts.tpl
        if ($params['template_file'] == 'catalog/_partials/product-discounts') {
            return 'module:'.$this->name.'/views/templates/hook/_partials/product_page_discounts_table.tpl';
        }
    }

    public function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitWkDisplayDiscountModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm($this->getConfigForm());
    }

    protected function formatQuantityDiscounts($specific_prices, $price, $tax_rate, $ecotax_amount)
    {
        $priceFormatter = new PriceFormatter();

        foreach ($specific_prices as $key => &$row) {
            $row['quantity'] = &$row['from_quantity'];
            if ($row['price'] >= 0) {
                // The price may be directly set

                /** @var float $currentPriceDefaultCurrency current price with taxes in default currency */
                $currentPriceDefaultCurrency = (!$row['reduction_tax'] ? $row['price'] :
                    $row['price'] * (1 + $tax_rate / 100)) + (float) $ecotax_amount;
                // Since this price is set in default currency,
                // we need to convert it into current currency
                $row['id_currency'];
                $currentPriceCurrentCurrency = Tools::convertPrice(
                    $currentPriceDefaultCurrency,
                    $this->context->currency,
                    true,
                    $this->context
                );

                if ($row['reduction_type'] == 'amount') {
                    $currentPriceCurrentCurrency -= ($row['reduction_tax'] ? $row['reduction'] :
                        $row['reduction'] / (1 + $tax_rate / 100));
                    $row['reduction_with_tax'] = $row['reduction_tax'] ? $row['reduction'] :
                        $row['reduction'] / (1 + $tax_rate / 100);
                } else {
                    $currentPriceCurrentCurrency *= 1 - $row['reduction'];
                }
                $row['real_value'] = $price > 0 ? $price - $currentPriceCurrentCurrency : $currentPriceCurrentCurrency;
                $discountPrice = $price - $row['real_value'];

                if (Configuration::get('PS_DISPLAY_DISCOUNT_PRICE')) {
                    if ($row['reduction_tax'] == 0 && !$row['price']) {
                        $row['discount'] = $priceFormatter->format($price - ($price * $row['reduction_with_tax']));
                    } else {
                        $row['discount'] = $priceFormatter->format($price - $row['real_value']);
                    }
                } else {
                    $row['discount'] = $priceFormatter->format($row['real_value']);
                }
            } else {
                if ($row['reduction_type'] == 'amount') {
                    if (Product::$_taxCalculationMethod == PS_TAX_INC) {
                        $row['real_value'] = $row['reduction_tax'] == 1 ? $row['reduction'] :
                            $row['reduction'] * (1 + $tax_rate / 100);
                    } else {
                        $row['real_value'] = $row['reduction_tax'] == 0 ? $row['reduction'] :
                            $row['reduction'] / (1 + $tax_rate / 100);
                    }
                    $row['reduction_with_tax'] = $row['reduction_tax'] ? $row['reduction'] :
                        $row['reduction'] + ($row['reduction'] * $tax_rate) / 100;
                    $discountPrice = $price - $row['real_value'];
                    if (Configuration::get('PS_DISPLAY_DISCOUNT_PRICE')) {
                        if ($row['reduction_tax'] == 0 && !$row['price']) {
                            $row['discount'] = $priceFormatter->format($price - ($price * $row['reduction_with_tax']));
                        } else {
                            $row['discount'] = $priceFormatter->format($price - $row['real_value']);
                        }
                    } else {
                        $row['discount'] = $priceFormatter->format($row['real_value']);
                    }
                } else {
                    $row['real_value'] = $row['reduction'] * 100;
                    $discountPrice = $price - $price * $row['reduction'];
                    if (Configuration::get('PS_DISPLAY_DISCOUNT_PRICE')) {
                        if ($row['reduction_tax'] == 0) {
                            $row['discount'] = $priceFormatter->format($price - ($price * $row['reduction_with_tax']));
                        } else {
                            $row['discount'] = $priceFormatter->format($price - ($price * $row['reduction']));
                        }
                    } else {
                        $row['discount'] = $row['real_value'] . '%';
                    }
                }
            }

            $row['save'] = $priceFormatter->format((($price * $row['quantity']) - ($discountPrice * $row['quantity'])));
            $row['nextQuantity'] = (isset($specific_prices[$key + 1]) ?
                (int) $specific_prices[$key + 1]['from_quantity'] : -1);
        }

        return $specific_prices;
    }

    protected function getConfigForm()
    {
        $path = 'wkdisplaydiscount/views/templates/hook/admin/_partials/position_input.tpl';
        $formFields = array(
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Product page'),
                        'icon' => 'icon-cogs',
                    ),
                    'input' => array(
                        array(
                            'type' => 'switch',
                            'label' => $this->l('Show on product page'),
                            'name' => 'WK_DISPLAY_DISCOUNT_ON_PRODUCT_PAGE',
                            'hint' => $this->l('Enable if you want to display the discount block on product page.'),
                            'values' => array(
                                array(
                                    'id' => 'WK_DISPLAY_DISCOUNT_ON_PRODUCT_PAGE_on',
                                    'value' => 1,
                                ),
                                array(
                                    'id' => 'WK_DISPLAY_DISCOUNT_ON_PRODUCT_PAGE_off',
                                    'value' => 0,
                                ),
                            ),
                        ),
                        array(
                            'type' => 'switch',
                            'label' => $this->l('Discount price on product page'),
                            'name' => 'WK_DISPLAY_DISCOUNT_PRICE_ON_PRODUCT_PAGE',
                            'hint' => $this->l('Enable if you want to display unit price in the discount block on product page.'),
                            'class' => 'price-on-product-pg',
                            'values' => array(
                                array(
                                    'id' => 'WK_DISPLAY_DISCOUNT_PRICE_ON_PRODUCT_PAGE_on',
                                    'value' => 1,
                                ),
                                array(
                                    'id' => 'WK_DISPLAY_DISCOUNT_PRICE_ON_PRODUCT_PAGE_off',
                                    'value' => 0,
                                ),
                            ),
                        ),
                        array(
                            'type' =>'html',
                            'class' => 'product-table-pos-cont',
                            'label' => $this->l('Select position to display discount block on product page'),
                            'hint' => $this->l('Enable if you want to display the discount block on product page.'),
                            'name' => 'WK_DISPLAY_DISCOUNT_POSITION',
                            'html_content' => $this->context->smarty->fetch(
                                _PS_MODULE_DIR_.$path
                            )
                        ),
                    ),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    ),
                )
            ),
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Product Listing pages'),
                        'icon' => 'icon-cogs',
                    ),
                    'input' => array(
                        array(
                            'type' => 'select',
                            'label' => $this->l('Select pages'),
                            'name' => 'WK_DISPLAY_DISCOUNT_LISTING_PAGES_TYPE',
                            'options' => array(
                                'query' => array(
                                    array(
                                        "id" => self::WK_PRODUCT_DISCOUNT_TABLE_ALL_PAGES,
                                        "name" => $this->l('All Pages')
                                    ),
                                    array(
                                        "id" => self::WK_PRODUCT_DISCOUNT_TABLE_SELECTED_PAGES,
                                        "name" => $this->l('Selected Pages')
                                    ),
                                ),
                                'id' => 'id',
                                'name' => 'name',
                            ),
                        ),
                        array(
                            'type' => 'checkbox',
                            'label' => $this->l('Product listing pages'),
                            'name' => 'WK_DISPLAY_DISCOUNT_SELECTED_LISTING_PAGES',
                            'class' => 'wk-pages-checkbox',
                            'values' => array(
                                'query' => array(
                                    array(
                                        'id' => self::WK_PRODUCT_DISCOUNT_TABLE_HOME_PAGE,
                                        'name' => $this->l('Home page'),
                                        'val' => self::WK_PRODUCT_DISCOUNT_TABLE_HOME_PAGE
                                    ),
                                    array(
                                        'id' => self::WK_PRODUCT_DISCOUNT_TABLE_CATEGORY_PAGE,
                                        'name' => $this->l('Category page'),
                                        'val' => self::WK_PRODUCT_DISCOUNT_TABLE_CATEGORY_PAGE
                                    ),
                                    array(
                                        'id' => self::WK_PRODUCT_DISCOUNT_TABLE_BEST_SALES,
                                        'name' => $this->l('Best-sales page'),
                                        'val' => self::WK_PRODUCT_DISCOUNT_TABLE_BEST_SALES
                                    ),
                                    array(
                                        'id' => self::WK_PRODUCT_DISCOUNT_TABLE_PRICES_DROP,
                                        'name' => $this->l('Prices-drop page'),
                                        'val' => self::WK_PRODUCT_DISCOUNT_TABLE_PRICES_DROP
                                    ),
                                    array(
                                        'id' => self::WK_PRODUCT_DISCOUNT_TABLE_NEW_PRODUCTS,
                                        'name' => $this->l('New-products page'),
                                        'val' => self::WK_PRODUCT_DISCOUNT_TABLE_NEW_PRODUCTS
                                    ),
                                    array(
                                        'id' => self::WK_PRODUCT_DISCOUNT_TABLE_SEARCH,
                                        'name' => $this->l('Search page'),
                                        'val' => self::WK_PRODUCT_DISCOUNT_TABLE_SEARCH
                                    ),
                                    array(
                                        'id' => self::WK_PRODUCT_DISCOUNT_TABLE_MANUFACTURER,
                                        'name' => $this->l('Manufacturer page'),
                                        'val' => self::WK_PRODUCT_DISCOUNT_TABLE_MANUFACTURER
                                    ),
                                    array(
                                        'id' => self::WK_PRODUCT_DISCOUNT_TABLE_SUPLIER,
                                        'name' => $this->l('Supplier page'),
                                        'val' => self::WK_PRODUCT_DISCOUNT_TABLE_SUPLIER
                                    ),
                                ),
                                'id' => 'id',
                                'name' => 'name',
                            ),
                        ),
                        array(
                            'type' => 'switch',
                            'label' => $this->l('Display Unit price'),
                            'name' => 'WK_DISPLAY_UNIT_PRICE_ON_PRODUCT_LISTING',
                            'hint' => $this->l('Enable if you want to display the unit price in discount block.'),
                            'values' => array(
                                array(
                                    'id' => 'WK_DISPLAY_UNIT_PRICE_ON_PRODUCT_LISTING_on',
                                    'value' => 1,
                                ),
                                array(
                                    'id' => 'WK_DISPLAY_UNIT_PRICE_ON_PRODUCT_LISTING_off',
                                    'value' => 0,
                                ),
                            ),
                        ),
                        array(
                            'type' => 'switch',
                            'label' => $this->l('Display discount table in mobile device'),
                            'name' => 'WK_DISPLAY_DISCOUNT_MOBILE_VIEW',
                            'hint' => $this->l('Enable if you want to display the discount block in mobile device.'),
                            'class' => 'price-on-cat-pg',
                            'values' => array(
                                array(
                                    'id' => 'WK_DISPLAY_DISCOUNT_MOBILE_VIEW_on',
                                    'value' => 1,
                                ),
                                array(
                                    'id' => 'WK_DISPLAY_DISCOUNT_MOBILE_VIEW_off',
                                    'value' => 0,
                                ),
                            ),
                        ),
                    ),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    ),
                )
            ),

        );
        return $formFields;
    }

    protected function getConfigFormValues()
    {

        $formValues =  array(
            'WK_DISPLAY_DISCOUNT_ON_PRODUCT_PAGE' => Configuration::get('WK_DISPLAY_DISCOUNT_ON_PRODUCT_PAGE', null),
            'WK_DISPLAY_DISCOUNT_PRICE_ON_PRODUCT_PAGE' => Configuration::get(
                'WK_DISPLAY_DISCOUNT_PRICE_ON_PRODUCT_PAGE',
                null
            ),
            'WK_DISPLAY_DISCOUNT_POSITION' => Configuration::get('WK_DISPLAY_DISCOUNT_POSITION', null),

            'WK_DISPLAY_DISCOUNT_LISTING_PAGES_TYPE' => Configuration::get(
                'WK_DISPLAY_DISCOUNT_LISTING_PAGES_TYPE',
                null
            ),

            'WK_DISPLAY_DISCOUNT_MOBILE_VIEW' => Configuration::get('WK_DISPLAY_DISCOUNT_MOBILE_VIEW', null),
            'WK_DISPLAY_UNIT_PRICE_ON_PRODUCT_LISTING' => Configuration::get(
                'WK_DISPLAY_UNIT_PRICE_ON_PRODUCT_LISTING',
                null
            ),
        );

        $selectedPages = Tools::jsonDecode(Configuration::get('WK_DISPLAY_DISCOUNT_SELECTED_LISTING_PAGES'));
        foreach ($this->discountDisplaySelectedPages as $pages) {
            $field_key = 'WK_DISPLAY_DISCOUNT_SELECTED_LISTING_PAGES_'.$pages['page_id'];
            $formValues[$field_key] = 0;
            if (is_array($selectedPages)) {
                if (in_array((int) $pages['page_id'], $selectedPages)) {
                    $formValues[$field_key] = 1;
                }
            }
        }

        return $formValues;
    }

    protected function postProcess()
    {
        $formValues = $this->getConfigFormValues();
        $selectedPages = array();
        foreach (array_keys($formValues) as $key) {
            if (strpos($key, 'WK_DISPLAY_DISCOUNT_SELECTED_LISTING_PAGES') !== false) {
                if ($pageId = Tools::getValue($key)) {
                    $selectedPages[] = $pageId;
                }
            } else {
                Configuration::updateValue($key, Tools::getValue($key));
            }
        }
        Configuration::updateValue('WK_DISPLAY_DISCOUNT_SELECTED_LISTING_PAGES', Tools::jsonEncode($selectedPages));

        Media::addJSDef(array(
            'selectedPages' => $selectedPages,
        ));
    }

    public function setDefaultConfigurations()
    {
        Configuration::updateValue('WK_DISPLAY_DISCOUNT_ON_PRODUCT_PAGE', 1);
        Configuration::updateValue('WK_DISPLAY_DISCOUNT_PRICE_ON_PRODUCT_PAGE', 1);
        Configuration::updateValue('WK_DISPLAY_DISCOUNT_POSITION', 1);

        Configuration::updateValue('WK_DISPLAY_DISCOUNT_MOBILE_VIEW', 1);
        Configuration::updateValue('WK_DISPLAY_DISCOUNT_LISTING_PAGES_TYPE', self::WK_PRODUCT_DISCOUNT_TABLE_ALL_PAGES);
        Configuration::updateValue('WK_DISPLAY_DISCOUNT_SELECTED_LISTING_PAGES', 1);
        Configuration::updateValue('WK_DISPLAY_UNIT_PRICE_ON_PRODUCT_LISTING', 1);

        return true;
    }

    public function deleteConfigurations()
    {
        $configKeys = array(
            'WK_DISPLAY_DISCOUNT_ON_PRODUCT_PAGE',
            'WK_DISPLAY_DISCOUNT_PRICE_ON_PRODUCT_PAGE',
            'WK_DISPLAY_DISCOUNT_POSITION',

            'WK_DISPLAY_DISCOUNT_MOBILE_VIEW',
            'WK_DISPLAY_DISCOUNT_LISTING_PAGES_TYPE',
            'WK_DISPLAY_DISCOUNT_SELECTED_LISTING_PAGES',
            'WK_DISPLAY_UNIT_PRICE_ON_PRODUCT_LISTING',
        );

        foreach ($configKeys as $key) {
            if (!Configuration::deleteByName($key)) {
                return false;
            }
        }
        return true;
    }

    public function hookActionAdmincontrollersetmedia($param)
    {
        if ('AdminModules' == Tools::getValue("controller")) {
            $this->context->smarty->assign(array(
                'module_dir' => _MODULE_DIR_.'wkdisplaydiscount/views/img/',
                'position_values' => array(
                   array(
                    self::WK_PRODUCT_DISCOUNT_TABLE_DEFAULT,
                    $this->l('Default position')
                   ),
                   array(
                    self::WK_PRODUCT_DISCOUNT_TABLE_AFTER_THUMBS,
                    $this->l('displayWkProductAfterThumb')
                   ),
                   array(
                    self::WK_PRODUCT_DISCOUNT_TABLE_ADDITIONAL_INFO,
                    $this->l('displayProductAdditionalInfo')
                   ),
                   array(
                    self::WK_PRODUCT_DISCOUNT_TABLE_PRICE_BLOCK,
                    $this->l('displayProductPriceBlock')
                   ),
                ),
                'selected_pos' => Configuration::get('WK_DISPLAY_DISCOUNT_POSITION', null),
            ));

            Media::addJsDef(array(
                'all_pages' => self::WK_PRODUCT_DISCOUNT_TABLE_ALL_PAGES,
                'selected_pages' => self::WK_PRODUCT_DISCOUNT_TABLE_SELECTED_PAGES
            ));
            $this->context->controller->addCSS(
                _MODULE_DIR_.'wkdisplaydiscount/views/css/wk_display_discount_admin.css'
            );
            $this->context->controller->addJS(
                _MODULE_DIR_.'wkdisplaydiscount/views/js/wk_display_discount_admin.js'
            );
        }
    }

    public function hookActionFrontControllerSetMedia($params)
    {
        Media::addJSDef(array(
            'current_controller' => Tools::getValue('controller'),
        ));
        // Add media if controller is product controller
        if (array_key_exists(Tools::getValue("controller"), $this->discountDisplaySelectedPages)
            || 'product' == Tools::getValue("controller")
        ) {
            $this->context->controller->addCSS(
                _MODULE_DIR_.'wkdisplaydiscount/views/css/wk_display_discount_front.css'
            );
            $this->context->controller->addJS(
                _MODULE_DIR_.'wkdisplaydiscount/views/js/wk_display_discount_front.js'
            );
        }
    }

    public function install()
    {
        if (!parent::install()
            || !$this->setDefaultConfigurations()
            || !$this->registerHook('filterProductContent')
            || !$this->registerHook('actionAdminControllerSetMedia')
            || !$this->registerHook('displayWkAfterProductThumbs')
            || !$this->registerHook('displayOverrideTemplate')
            || !$this->registerHook('displayProductAdditionalInfo')
            || !$this->registerHook('displayProductPriceBlock')
            || !$this->registerHook('actionFrontControllerSetMedia')
        ) {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()
            || !$this->deleteConfigurations()
        ) {
            return false;
        }
        return true;
    }
}
