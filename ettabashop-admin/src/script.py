import re

with open('resources/views/admin/products/create.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update form ID and Rate/Payable amount fields
content = content.replace('<form class="form-horizontal" action="{{ route(\'product.store\') }}" method="post"', '<form id="productForm" class="form-horizontal" action="{{ route(\'product.store\') }}" method="post"')

seller_rate_old = '''<div class="col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="priceEn">Seller Rate</label>
                                                    <input type="text" min="0" class="form-control" id="priceEn"
                                                           name="rate_en" placeholder="Rate">
                                                </div>
                                            </div>'''

seller_rate_new = '''<div class="col-md-3 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="sellerPayable">Seller Payable Amount</label>
                                                    <input type="text" min="0" class="form-control" id="sellerPayable"
                                                           readonly placeholder="Payable Amount">
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="priceEn">Seller Rate with VAT</label>
                                                    <input type="text" min="0" class="form-control" id="priceEn"
                                                           name="rate_en" placeholder="Rate" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                                </div>
                                            </div>'''
content = content.replace(seller_rate_old, seller_rate_new)

# 2. Add oninput to MRP, ERP, CB, Refer
content = content.replace('name="mrp_en" placeholder="Price">', 'name="mrp_en" placeholder="Price" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\')">')
content = content.replace('name="erp_en" placeholder="Ettaba Retail Price">', 'name="erp_en" placeholder="Ettaba Retail Price" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\')"><small id="erpError" class="text-danger" style="display:none;font-weight:bold;">Seller Rate ???? ERP ?? ????? ??????? ? ???? ??????? ????? ????</small>')
content = content.replace('name="cb_en" placeholder="Direct Customer Cashback">', 'name="cb_en" placeholder="Direct Customer Cashback" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\')"><small id="cbHelper" class="text-primary font-weight-bold"></small>')
content = content.replace('name="direct_refer_commission" placeholder="Amount">', 'name="direct_refer_commission" placeholder="Amount" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\')"><small id="referHelper" class="text-primary font-weight-bold"></small>')
content = content.replace('name="quantity" placeholder="Quantity">', 'name="quantity" placeholder="Quantity" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\')">')
content = content.replace('name="vat_percent" placeholder="VAT">', 'name="vat_percent" placeholder="VAT" readonly>')

# 3. Modify Javascript
script_old = '''function calculateTRP() {
                let rate = parseFloat(input[name="rate_en"].val()) || 0;
                let erp = parseFloat(#erp_en.val()) || 0;
                let cb = parseFloat(#cb_en.val()) || 0;
                let directRefer = parseFloat(#direct_refer_commission.val()) || 0;

                let trp = (erp - (rate + cb + directRefer)) / 25;
                if (trp < 0) trp = 0;
                
                #trp_en_readonly.val(trp.toFixed(2));

                let tcb = cb + (trp * 2);
                #tcb_en_readonly.val(tcb.toFixed(2));
                
                let vat = erp * 0.15;
                #vat.val(vat.toFixed(2));
            }

            input[name="rate_en"], #erp_en, #cb_en, #direct_refer_commission.on('input', function() {
                calculateTRP();
            });'''

script_new = '''function calculateTRP() {
                let rate = parseFloat(input[name="rate_en"].val()) || 0;
                let erp = parseFloat(#erp_en.val()) || 0;
                let cb = parseFloat(#cb_en.val()) || 0;
                let directRefer = parseFloat(#direct_refer_commission.val()) || 0;
                
                let vat = erp * 0.15;
                #vat.val(vat.toFixed(2));

                let payable = rate - vat;
                #sellerPayable.val(payable > 0 ? payable.toFixed(2) : 0);

                let netErp = erp - vat;
                let maxLimit = netErp * 0.25;
                if(maxLimit < 0) maxLimit = 0;
                
                #cbHelper.text('Max allowed: ?' + maxLimit.toFixed(2));
                #referHelper.text('Max allowed: ?' + maxLimit.toFixed(2));
                
                if(cb > maxLimit) {
                    #cb_en.css('border-color', 'red');
                } else {
                    #cb_en.css('border-color', '');
                }
                
                if(directRefer > maxLimit) {
                    #direct_refer_commission.css('border-color', 'red');
                } else {
                    #direct_refer_commission.css('border-color', '');
                }
                
                if(erp > 0 && erp < (rate + 5)) {
                    #erp_en.css('border-color', 'red');
                    #erpError.show();
                } else {
                    #erp_en.css('border-color', '');
                    #erpError.hide();
                }

                let trp = (erp - (rate + cb + directRefer)) / 25;
                if (trp < 0) trp = 0;
                
                #trp_en_readonly.val(trp.toFixed(2));

                let tcb = cb + (trp * 2);
                #tcb_en_readonly.val(tcb.toFixed(2));
            }

            input[name="rate_en"], #erp_en, #cb_en, #direct_refer_commission.on('input', function() {
                calculateTRP();
            });

            #productForm.on('submit', function(e){
                let rate = parseFloat(input[name="rate_en"].val()) || 0;
                let erp = parseFloat(#erp_en.val()) || 0;
                let cb = parseFloat(#cb_en.val()) || 0;
                let directRefer = parseFloat(#direct_refer_commission.val()) || 0;
                let vat = erp * 0.15;
                let netErp = erp - vat;
                let maxLimit = netErp * 0.25;
                
                if(erp < (rate + 5)) {
                    e.preventDefault();
                    alert('Seller Rate ???? ERP ?? ????? ??????? ? ???? ??????? ????? ????');
                    return false;
                }
                if(cb > maxLimit) {
                    e.preventDefault();
                    alert('Direct Customer Cashback Max limit is ' + maxLimit.toFixed(2));
                    return false;
                }
                if(directRefer > maxLimit) {
                    e.preventDefault();
                    alert('Direct Refer Commission Max limit is ' + maxLimit.toFixed(2));
                    return false;
                }
                return true;
            });
'''
content = content.replace(script_old, script_new)

with open('resources/views/admin/products/create.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("create.blade.php processed successfully!")
