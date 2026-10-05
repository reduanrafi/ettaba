@extends('admin.layouts.layout')

@section('extra-style')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

    <style>
        .seller-app-wrapper {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #1e293b;
            max-width: 980px;
            margin: 0 auto;
            padding-bottom: 40px;
        }
        .app-header-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: #ffffff;
            border-radius: 18px;
            padding: 22px 24px;
            margin-bottom: 22px;
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.3);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .app-header-title {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .app-header-subtitle {
            margin: 4px 0 0 0;
            font-size: 13px;
            opacity: 0.9;
            font-weight: 500;
        }
        .app-header-btn {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.35);
            padding: 8px 18px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.2s ease;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .app-header-btn:hover {
            background: #ffffff;
            color: #1e3a8a !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-1px);
        }

        .app-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 22px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
            border: 1px solid #edf2f7;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .app-card-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .app-card-title i {
            color: #3b82f6;
            font-size: 16px;
        }

        .app-form-group {
            margin-bottom: 18px;
        }
        .app-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }
        .app-label .required {
            color: #ef4444;
            margin-left: 2px;
        }
        .app-input, .app-textarea, .select2-container--bootstrap4 .select2-selection {
            width: 100% !important;
            background: #f8fafc !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 12px !important;
            padding: 10px 14px !important;
            font-size: 14px !important;
            color: #0f172a !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
            box-shadow: none !important;
            outline: none !important;
            min-height: 46px;
        }
        .app-input:focus, .app-textarea:focus, .select2-container--bootstrap4.select2-container--focus .select2-selection {
            background: #ffffff !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12) !important;
        }
        .app-input[readonly] {
            background: #f1f5f9 !important;
            color: #64748b !important;
            cursor: not-allowed;
            border-color: #e2e8f0 !important;
        }
        .app-textarea {
            resize: vertical;
            min-height: 100px;
            line-height: 1.6;
        }

        /* Dynamic Helper Badge */
        .app-badge-helper {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            margin-top: 6px;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            transition: all 0.2s ease;
        }
        .app-badge-helper.limit-exceeded {
            background: #fef2f2 !important;
            color: #b91c1c !important;
            border-color: #fecaca !important;
        }
        .app-input-error {
            border-color: #ef4444 !important;
            background: #fff5f5 !important;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.12) !important;
        }

        /* Live Calculation Dashboard */
        .calc-dashboard {
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
            border-radius: 14px;
            padding: 16px;
            border: 1px solid #e2e8f0;
            margin-top: 6px;
        }
        .calc-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed #cbd5e1;
            font-size: 13px;
        }
        .calc-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .calc-item-label {
            color: #475569;
            font-weight: 500;
        }
        .calc-item-value {
            font-weight: 700;
            color: #0f172a;
        }
        .calc-pool-badge {
            background: #dbeafe;
            color: #1e40af;
            padding: 2px 8px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
        }

        /* Upload preview box */
        .image-upload-wrapper {
            position: relative;
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            padding: 20px;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .image-upload-wrapper:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }
        .image-upload-icon {
            font-size: 32px;
            color: #94a3b8;
            margin-bottom: 8px;
        }
        .image-preview-box {
            margin-top: 12px;
            text-align: center;
        }
        .image-preview-box img {
            max-height: 160px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            object-fit: cover;
        }

        /* Submit Button */
        .app-btn-submit {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff !important;
            border: none;
            padding: 14px 28px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 8px 20px -4px rgba(16, 185, 129, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .app-btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -4px rgba(16, 185, 129, 0.5);
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        @media (max-width: 768px) {
            .app-header-card {
                padding: 18px;
                border-radius: 14px;
            }
            .app-card {
                padding: 16px;
                border-radius: 14px;
            }
            .app-btn-submit {
                font-size: 15px;
                padding: 14px 20px;
            }
        }
    </style>
@endsection

@section('content')
    <section class="content" style="padding-top: 15px;">
        <div class="seller-app-wrapper">
            
            <!-- App Header -->
            <div class="app-header-card">
                <div>
                    <h1 class="app-header-title">
                        <i class="fa fa-pencil-square-o"></i> Edit Product
                    </h1>
                    <p class="app-header-subtitle">পণ্য আপডেট করুন: <strong>{{ $product->name_en }}</strong> (Code: #{{ $product->unique_id }})</p>
                </div>
                <div>
                    <a href="{{ route('product.index') }}" class="app-header-btn">
                        <i class="fa fa-list"></i> All Products
                    </a>
                </div>
            </div>

            <!-- Flash Messages -->
            @if(Session::has('success'))
                <div class="alert alert-success" style="border-radius: 12px; font-weight: 600; box-shadow: 0 4px 12px rgba(16,185,129,0.15);">
                    <i class="fa fa-check-circle"></i> {{ Session::get('success') }}
                </div>
            @elseif(Session::has('error'))
                <div class="alert alert-danger" style="border-radius: 12px; font-weight: 600; box-shadow: 0 4px 12px rgba(239,68,68,0.15);">
                    <i class="fa fa-exclamation-circle"></i> {{ Session::get('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" style="border-radius: 12px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="productForm" action="{{ route('product.update', ['product' => $product->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                {!! method_field('PUT') !!}

                <!-- Section 1: Basic Information -->
                <div class="app-card">
                    <div class="app-card-title">
                        <i class="fa fa-cube"></i> Basic Product Information
                    </div>

                    <div class="row">
                        <div class="col-md-5 col-sm-12">
                            <div class="app-form-group">
                                <label class="app-label" for="productNameEn">
                                    Product Name <span class="required">*</span>
                                </label>
                                <input type="text" class="app-input" id="productNameEn" name="name_en" required
                                       placeholder="Product Name" value="{{ old('name_en', $product->name_en) }}">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="app-form-group">
                                <label class="app-label" for="slug">
                                    Product Slug
                                </label>
                                <input type="text" class="app-input" id="slug" readonly
                                       value="{{ $product->slug }}" placeholder="slug">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="app-form-group">
                                <label class="app-label" for="selectCategory">
                                    Category <span class="required">*</span>
                                </label>
                                <select name="category_id" class="form-control" id="selectCategory" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                            {{ $category->name_bn }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Description (Native, App-like, Emoji Supported) -->
                    <div class="app-form-group">
                        <label class="app-label" for="description_en">
                            Product Description (Bangla / English)
                            <small class="text-muted" style="font-weight: normal; margin-left: 4px;">(ইমোজি ও টেক্সট সরাসরি লিখতে পারবেন)</small>
                        </label>
                        <textarea id="description_en" name="description_en" class="app-textarea" rows="5"
                                  placeholder="পণ্যের বিস্তারিত বিবরণ লিখুন... 🌟✨">{{ old('description_en', $product->description_en) }}</textarea>
                    </div>

                    <!-- Delivery Area and Fee -->
                    <div class="app-form-group" style="margin-bottom: 0;">
                        <label class="app-label" for="delivery_area_en">
                            <i class="fa fa-truck text-muted"></i> Delivery Area & Delivery Fee
                        </label>
                        <textarea id="delivery_area_en" name="delivery_area_en" class="app-textarea" rows="3"
                                  placeholder="ডেলিভারি এরিয়া এবং চার্জ লিখুন...">{{ old('delivery_area_en', $product->delivery_area_en) }}</textarea>
                    </div>
                </div>

                <!-- Section 2: Pricing & Commission Rules -->
                <div class="app-card">
                    <div class="app-card-title">
                        <i class="fa fa-tag"></i> Pricing & Reward Settings
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="app-form-group">
                                <label class="app-label" for="priceEn">
                                    Seller Rate with VAT (৳) <span class="required">*</span>
                                </label>
                                <input type="text" class="app-input" id="priceEn" name="rate_en" required
                                       placeholder="e.g. 130" value="{{ old('rate_en', $product->rate_en) }}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="app-form-group">
                                <label class="app-label" for="erp_en">
                                    ERP (Ettaba Retail Price) (৳) <span class="required">*</span>
                                </label>
                                <input type="text" class="app-input" id="erp_en" name="erp_en" required
                                       placeholder="e.g. 230" value="{{ old('erp_en', $product->erp_en) }}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                <div id="erpError" style="display: none; color: #dc2626; font-size: 12px; font-weight: 700; margin-top: 5px;">
                                    <i class="fa fa-exclamation-triangle"></i> Seller Rate এবং ERP এর মধ্যে কমপক্ষে ১০ টাকা ব্যবধান থাকতে হবে!
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="app-form-group">
                                <label class="app-label" for="mrp_en">
                                    MRP (Market Retail Price) (৳) <span class="required">*</span>
                                </label>
                                <input type="text" class="app-input" id="mrp_en" name="mrp_en" required
                                       placeholder="e.g. 250" value="{{ old('mrp_en', $product->mrp_en) }}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                            </div>
                        </div>
                    </div>

                    <!-- Direct Cashback & Direct Refer Commission (50% Combined Pool) -->
                    <div class="row" style="margin-top: 8px;">
                        <div class="col-md-6 col-sm-12">
                            <div class="app-form-group">
                                <label class="app-label" for="cb_en">
                                    Direct Customer Cashback (৳)
                                </label>
                                <input type="text" class="app-input" id="cb_en" name="cb_en"
                                       placeholder="0.00" value="{{ old('cb_en', $product->cb_en) }}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                <div id="cbHelper" class="app-badge-helper">
                                    <i class="fa fa-info-circle"></i> Max allowed: ৳0.00
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="app-form-group">
                                <label class="app-label" for="direct_refer_commission">
                                    Direct Refer Commission (৳)
                                </label>
                                <input type="text" class="app-input" id="direct_refer_commission" name="direct_refer_commission"
                                       placeholder="0.00" value="{{ old('direct_refer_commission', $product->direct_refer_commission) }}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                <div id="referHelper" class="app-badge-helper">
                                    <i class="fa fa-info-circle"></i> Max allowed: ৳0.00
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Financial & Profit Protection Dashboard -->
                    <div class="calc-dashboard">
                        <div style="font-weight: 700; color: #1e3a8a; font-size: 13px; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
                            <span><i class="fa fa-shield"></i> Financial & 50% Margin Protection Summary</span>
                            <span id="rateDifferenceBadge" class="calc-pool-badge">Diff: ৳0.00</span>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="calc-item">
                                    <span class="calc-item-label">Net ERP Amount (ERP - Seller Rate):</span>
                                    <span class="calc-item-value" id="netMarginText">৳0.00</span>
                                </div>
                                <div class="calc-item">
                                    <span class="calc-item-label">Max 50% Reward Pool (Cashback + Refer):</span>
                                    <span class="calc-item-value text-primary" id="maxRewardPoolText">৳0.00</span>
                                </div>
                                <div class="calc-item">
                                    <span class="calc-item-label">Seller Payable Amount (Rate - VAT):</span>
                                    <span class="calc-item-value text-success" id="sellerPayableText">৳0.00</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="calc-item">
                                    <span class="calc-item-label">Calculated TRP:</span>
                                    <span class="calc-item-value" id="trpText">0.00</span>
                                </div>
                                <div class="calc-item">
                                    <span class="calc-item-label">Calculated TCB:</span>
                                    <span class="calc-item-value" id="tcbText">0.00</span>
                                </div>
                                <div class="calc-item">
                                    <span class="calc-item-label">VAT (15% of ERP):</span>
                                    <span class="calc-item-value" id="vatText">৳0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden calculated fields sent with request -->
                    <input type="hidden" id="sellerPayable" name="seller_payable" value="0">
                    <input type="hidden" id="TRPen" name="trp_en" value="{{ $product->trp_en }}">
                    <input type="hidden" id="tcb_en" name="tcb_en" value="{{ $product->tcb_en }}">
                    <input type="hidden" id="vat" name="vat_percent" value="{{ $product->vat_percent }}">
                </div>

                <!-- Section 3: Inventory & Media -->
                <div class="app-card">
                    <div class="app-card-title">
                        <i class="fa fa-archive"></i> Inventory & Media
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="app-form-group">
                                <label class="app-label" for="quantity">
                                    Available Stock / Quantity <span class="required">*</span>
                                </label>
                                <input type="text" class="app-input" id="quantity" name="quantity" required
                                       placeholder="e.g. 50" value="{{ old('quantity', $product->quantity) }}"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="app-form-group">
                                <label class="app-label" for="unit">
                                    Unit <small class="text-muted">(Pcs, Kg, Box, Litre, etc.)</small>
                                </label>
                                <input type="text" class="app-input" id="unit" name="unit"
                                       placeholder="e.g. Pcs" value="{{ old('unit', $product->unit) }}">
                            </div>
                        </div>
                    </div>

                    <div class="app-form-group" style="margin-bottom: 0;">
                        <label class="app-label">Featured Image</label>
                        <div class="image-upload-wrapper" onclick="document.getElementById('productImageInput').click();">
                            <i class="fa fa-cloud-upload image-upload-icon"></i>
                            <div style="font-weight: 600; color: #1e293b; font-size: 14px;">Click to change product image</div>
                            <div style="font-size: 12px; color: #64748b; margin-top: 4px;">PNG, JPG, JPEG (Square recommended, max 2MB)</div>
                            <input type="file" id="productImageInput" name="featured_image" accept="image/*" style="display: none;" onchange="previewProductImage(this);">
                            
                            <div class="image-preview-box" id="imagePreviewContainer" style="{{ !empty($product->featured_image) ? 'display: block;' : '' }}">
                                <img id="imagePreviewImg" src="{{ !empty($product->featured_image) ? asset($product->featured_image) : '' }}" alt="Product Image">
                                <div style="margin-top: 6px; font-size: 12px; color: #10b981; font-weight: 600;">Current Image</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Action -->
                <div style="margin-top: 24px;">
                    <button type="submit" id="submitBtn" class="app-btn-submit">
                        <i class="fa fa-save"></i> Update Product
                    </button>
                </div>
            </form>

        </div>
    </section>
@endsection

@section('extra-script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $('#selectCategory').select2({
            theme: 'bootstrap4',
            placeholder: 'Select a Category'
        });

        function previewProductImage(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreviewImg').attr('src', e.target.result);
                    $('#imagePreviewContainer').fadeIn(200);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(function () {
            function recalculateProductFinancials() {
                let rate = parseFloat($('#priceEn').val()) || 0;
                let erp = parseFloat($('#erp_en').val()) || 0;
                let cb = parseFloat($('#cb_en').val()) || 0;
                let directRefer = parseFloat($('#direct_refer_commission').val()) || 0;

                // VAT calculation
                let vat = erp * 0.15;
                $('#vat').val(vat.toFixed(2));
                $('#vatText').text('৳ ' + vat.toFixed(2));

                // Seller Payable calculation
                let payable = rate - vat;
                let payableFormatted = (payable > 0 ? payable.toFixed(2) : '0.00');
                $('#sellerPayable').val(payableFormatted);
                $('#sellerPayableText').text('৳ ' + payableFormatted);

                // Rule 3: Seller Rate vs ERP difference at least 10 Tk
                let diff = erp - rate;
                $('#rateDifferenceBadge').text('Diff: ৳' + diff.toFixed(2));

                if (erp > 0 && erp < (rate + 10)) {
                    $('#erp_en').addClass('app-input-error');
                    $('#erpError').show();
                    $('#rateDifferenceBadge').css({'background': '#fee2e2', 'color': '#b91c1c'});
                } else {
                    $('#erp_en').removeClass('app-input-error');
                    $('#erpError').hide();
                    $('#rateDifferenceBadge').css({'background': '#dbeafe', 'color': '#1e40af'});
                }

                // Rule 4: Net ERP Amount = (ERP - Seller Rate)
                let netMargin = Math.max(0, erp - rate);
                let totalMaxRewardPool = netMargin * 0.50; // 50% pool

                $('#netMarginText').text('৳ ' + netMargin.toFixed(2));
                $('#maxRewardPoolText').text('৳ ' + totalMaxRewardPool.toFixed(2));

                // Dynamic helper text for Cashback and Refer Commission
                let maxAllowedForCb = Math.max(0, totalMaxRewardPool - directRefer);
                let maxAllowedForRefer = Math.max(0, totalMaxRewardPool - cb);

                $('#cbHelper').html('<i class="fa fa-info-circle"></i> Max allowed: ৳' + maxAllowedForCb.toFixed(2));
                $('#referHelper').html('<i class="fa fa-info-circle"></i> Max allowed: ৳' + maxAllowedForRefer.toFixed(2));

                // Input validation styling
                let totalEnteredRewards = cb + directRefer;
                let isExceeded = totalEnteredRewards > (totalMaxRewardPool + 0.01);

                if (isExceeded || cb > (maxAllowedForCb + 0.01)) {
                    $('#cb_en').addClass('app-input-error');
                    $('#cbHelper').addClass('limit-exceeded').html('<i class="fa fa-times-circle"></i> Limit exceeded! Max: ৳' + maxAllowedForCb.toFixed(2));
                } else {
                    $('#cb_en').removeClass('app-input-error');
                    $('#cbHelper').removeClass('limit-exceeded');
                }

                if (isExceeded || directRefer > (maxAllowedForRefer + 0.01)) {
                    $('#direct_refer_commission').addClass('app-input-error');
                    $('#referHelper').addClass('limit-exceeded').html('<i class="fa fa-times-circle"></i> Limit exceeded! Max: ৳' + maxAllowedForRefer.toFixed(2));
                } else {
                    $('#direct_refer_commission').removeClass('app-input-error');
                    $('#referHelper').removeClass('limit-exceeded');
                }

                // TRP & TCB calculations
                let trp = (erp - (rate + cb + directRefer)) / 25;
                if (trp < 0) trp = 0;
                $('#TRPen').val(trp.toFixed(2));
                $('#trpText').text(trp.toFixed(2));

                let tcb = cb + (trp * 2);
                $('#tcb_en').val(tcb.toFixed(2));
                $('#tcbText').text(tcb.toFixed(2));
            }

            $('#priceEn, #erp_en, #cb_en, #direct_refer_commission').on('input', function() {
                recalculateProductFinancials();
            });

            // Initial calculation run
            recalculateProductFinancials();

            // Client-side submit guard
            $('#productForm').on('submit', function(e) {
                let rate = parseFloat($('#priceEn').val()) || 0;
                let erp = parseFloat($('#erp_en').val()) || 0;
                let cb = parseFloat($('#cb_en').val()) || 0;
                let directRefer = parseFloat($('#direct_refer_commission').val()) || 0;
                let netMargin = Math.max(0, erp - rate);
                let totalMaxRewardPool = netMargin * 0.50;

                if (erp > 0 && erp < (rate + 10)) {
                    e.preventDefault();
                    alert('Seller Rate থেকে ERP এর মধ্যে কমপক্ষে ১০ টাকা ব্যবধান থাকতে হবে।');
                    $('#erp_en').focus();
                    return false;
                }

                if ((cb + directRefer) > (totalMaxRewardPool + 0.05)) {
                    e.preventDefault();
                    alert('Direct Customer Cashback ও Direct Refer Commission মিলে সর্বোচ্চ ৳' + totalMaxRewardPool.toFixed(2) + ' দেওয়া যাবে (Net Margin এর ৫০%)।');
                    return false;
                }

                return true;
            });
        });
    </script>
@endsection