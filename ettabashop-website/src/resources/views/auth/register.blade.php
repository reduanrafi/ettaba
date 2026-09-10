@extends('auth.layout')

@section('content')
    <header>
        <div class="container">
            <div class="header-data">
                <div class="logo">
                    <a href="{{ route('website.index') }}" title=""><img src="{{ asset('assets/images/logo/logo.png') }}"
                            alt=""></a>
                </div>



                <div class="user-account">
                    <div class="user-info">
                        @if (\Illuminate\Support\Facades\Auth::user())
                            <a href="#" title="">{{ \Illuminate\Support\Facades\Auth::user()->name }}</a>
                            <i class="bi bi-user"></i>
                        @else
                            <a href="{{ route('website.index') }}" title="">Home</a>
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </header>
    <div class="sign-in-page">
        <div class="signin-popup">
            <div class="signin-pop">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="cmp-info">
                            <div class="cm-logo">
                                <img src="{{ asset('assets/images/cm-logo.png') }}" alt="">
                                <h3>1. General Account বা সাধারণ কাস্টমার</h3>
                                <p>
                                    General Account নির্বাচনের দ্বারা একজন কাস্টমার শুধুমাত্র পণ্যের সাথে থাকা TCB অর্থাৎ
                                    টোটাল ক্যাশব্যাক প্রাপ্ত হবেন।
                                    অন্য কোনো বোনাস এই একাউন্টের জন্য প্রযোজ্য নয় এবং এই ধরনের একাউন্টের জন্য কোনো শর্তও
                                    প্রযোজ্য নয়।</p>


                            </div>
                            <div class="cm-logo">
                                <h3>2. Partner Account বা পার্টনার কাস্টমার</h3>
                                <p>Partner Account নির্বাচনের দ্বারা একজন কাস্টমার পণ্যের সাথে থাকা TCB অর্থাৎ টোটাল
                                    ক্যাশব্যাকের পাশাপাশি কোম্পানির অন্যান্য সকল বোনাস প্রাপ্ত হবে। এবং এই ধরনের একাউন্টের
                                    জন্য কিছু শর্ত প্রযোজ্য হবে। বিস্তারিত আপনার রেফারকৃত ব্যক্তির কাছে জেনে নিন।</p>
                            </div>
                            {{-- <img src="{{ asset('assets/auth/images/banner.png') }}" alt=""> --}}
                        </div>
                    </div>



                    <div class="col-lg-6">
                        <div class="login-sec">
                            <ul class="sign-control">
                                <li data-tab="tab-1" class="current"><a href=""
                                        title="">{{ __('forms.signUp') }}</a></li>

                            </ul>
                            <div class="sign_in_sec current" id="tab-1">
                                <h3>{{ __('forms.signUp') }}</h3>

                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <div class="col-md-12  "id="messageDiv">
                                        @if (Session::has('success'))
                                            @include('website.layouts.message.success')
                                        @elseif(Session::has('error'))
                                            @include('website.layouts.message.error')
                                        @endif
                                    </div>
                                    <div class="row">
                                        <!-- 1. Name -->
                                        <div class="col-lg-12 no-pdd">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="sn-field mb-0 w-100">
                                                    <input type="text" name="name"
                                                        placeholder="{{ __('forms.fullNamePlaceholder') }}">
                                                    <i>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                                            <path
                                                                d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                                                        </svg>
                                                    </i>
                                                </div>
                                                <div style="margin-left: 10px; flex-shrink: 0;">
                                                    <span data-toggle="tooltip" data-placement="top"
                                                        title="নাম আপনার NID অনুযায়ী হতে হবে" style="cursor: pointer; background: #e44d3a; color: white; padding: 2px 8px; border-radius: 50%; font-size: 14px; display: inline-block;">?</span>
                                                </div>
                                            </div>
                                            @error('name')
                                                <div class="text-danger error">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- 2. Phone -->
                                        <div class="col-lg-12 no-pdd">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="sn-field mb-0 w-100">
                                                    <input type="text" name="phone"
                                                        placeholder="{{ __('forms.phonePlaceholder') }}" required>
                                                    <i>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-phone" viewBox="0 0 16 16">
                                                            <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h6zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H5z"/>
                                                            <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                                        </svg>
                                                    </i>
                                                </div>
                                                <div style="margin-left: 10px; flex-shrink: 0;">
                                                    <span data-toggle="tooltip" data-placement="top"
                                                        title="আপনার ফোন নাম্বার দিন" style="cursor: pointer; background: #e44d3a; color: white; padding: 2px 8px; border-radius: 50%; font-size: 14px; display: inline-block;">?</span>
                                                </div>
                                            </div>
                                            @error('phone')
                                                <div class="text-danger error">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- 3. Account Type -->
                                        <div class="col-lg-12 no-pdd">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="sn-field mb-0 w-100">
                                                    <select class="form-control" name="customer_type" id="customer_type_select" required>
                                                        <option value="">একাউন্ট নির্বাচন করুন</option>
                                                        <option value="buy_only">Customer Account</option>
                                                        <option value="buy_earn">Partner Account</option>
                                                        <option value="direct_selling">Direct Selling Account</option>
                                                    </select>
                                                    <i>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-person-badge" viewBox="0 0 16 16">
                                                            <path d="M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                                            <path d="M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0h-7zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5v10.795a4.2 4.2 0 0 0-.776-.492C11.392 12.387 10.063 12 8 12s-3.392.387-4.224.803a4.2 4.2 0 0 0-.776.492V2.5z"/>
                                                        </svg>
                                                    </i>
                                                </div>
                                                <div style="margin-left: 10px; flex-shrink: 0;">
                                                    <span data-toggle="tooltip" data-placement="top"
                                                        title="প্রতিমাসে একবার পরিবর্তন করা যাবে" style="cursor: pointer; background: #e44d3a; color: white; padding: 2px 8px; border-radius: 50%; font-size: 14px; display: inline-block;">?</span>
                                                </div>
                                            </div>
                                            @error('customer_type')
                                                <div class="text-danger error">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- 4. Account Number (conditional) -->
                                        <div class="col-lg-12 no-pdd" id="account_number_wrapper" style="display: none;">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="sn-field mb-0 w-100">
                                                    <select class="form-control" name="account_number">
                                                        <option value="">কত নম্বর একাউন্ট নির্বাচন করুন</option>
                                                        <option value="first">প্রথম একাউন্ট</option>
                                                        <option value="subsequent">দ্বিতীয় বা পরবর্তী একাউন্ট</option>
                                                    </select>
                                                    <i>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-list-ol" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5z"/>
                                                            <path d="M1.713 11.865v-.474H2c.217 0 .363-.137.363-.317 0-.185-.158-.31-.361-.31-.223 0-.367.152-.373.31h-.59c.016-.467.373-.787.986-.787.588-.002.954.291.957.703a.595.595 0 0 1-.492.594v.033a.615.615 0 0 1 .569.631c.003.533-.502.8-1.051.8-.656 0-1-.37-1.008-.794h.582c.008.178.186.306.422.309.254 0 .424-.145.422-.35-.002-.195-.155-.348-.414-.348h-.3zm-.004-4.699h-.604v-.035c0-.408.295-.844.958-.844.583 0 .96.326.96.756 0 .389-.257.617-.476.848l-.537.572v.03h1.054v.466H1.14v-.399l.704-.73c.197-.205.414-.39.414-.646 0-.281-.223-.47-.487-.47-.24 0-.436.14-.457.352v.05zM1.753 1.98h-.61v-.036c0-.408.293-.844.958-.844.582 0 .96.326.96.756 0 .389-.257.617-.476.848l-.537.572v.03h1.054v.466H1.14v-.399l.704-.73c.197-.205.414-.39.414-.646 0-.281-.223-.47-.487-.47-.24 0-.436.14-.457.352v.05z"/>
                                                        </svg>
                                                    </i>
                                                </div>
                                                <div style="margin-left: 10px; flex-shrink: 0;">
                                                    <span data-toggle="tooltip" data-placement="top"
                                                        title="কত নম্বর একাউন্ট নির্বাচন করুন" style="cursor: pointer; background: #e44d3a; color: white; padding: 2px 8px; border-radius: 50%; font-size: 14px; display: inline-block;">?</span>
                                                </div>
                                            </div>
                                            @error('account_number')
                                                <div class="text-danger error">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- 5. Referral -->
                                        <div class="col-lg-12 no-pdd" id="referral_code_wrapper">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="sn-field mb-0 w-100">
                                                    <input type="text" style="color:#C17A74" name="referral_code"
                                                        placeholder="প্লেসমেন্ট eID লিখুন"
                                                        @if (Request::get('ref')) value="{{ Request::get('ref') }}"
                                                           readonly @endif>
                                                    <i>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
                                                            <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                                            <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                                                        </svg>
                                                    </i>
                                                </div>
                                                <div style="margin-left: 10px; flex-shrink: 0;">
                                                    <span data-toggle="tooltip" data-placement="top"
                                                        title="ইত্তেবা শপে পুরবে থেকেই একাউন্ট আছে এমন কারো রেফার প্রয়োজন । অনুগ্রহ করে আপনার পরিচিত কারো eID লিখুন !  " style="cursor: pointer; background: #e44d3a; color: white; padding: 2px 8px; border-radius: 50%; font-size: 14px; display: inline-block;">?</span>
                                                </div>
                                            </div>
                                            @error('referral_code')
                                                <div class="text-danger error">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- 6. Referral-2 -->
                                        <div class="col-lg-12 no-pdd" id="referral_code_2_wrapper">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="sn-field mb-0 w-100">
                                                    <input type="text" style="color:#C17A74" name="referral_code_2"
                                                        placeholder="রেফার eID লিখুন">
                                                    <i>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                                                            <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                            <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                                                        </svg>
                                                    </i>
                                                </div>
                                                <div style="margin-left: 10px; flex-shrink: 0;">
                                                    <span data-toggle="tooltip" data-placement="top"
                                                        title="রেফার-২ এর eID দিন" style="cursor: pointer; background: #e44d3a; color: white; padding: 2px 8px; border-radius: 50%; font-size: 14px; display: inline-block;">?</span>
                                                </div>
                                            </div>
                                            @error('referral_code_2')
                                                <div class="text-danger error">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- 7. Password -->
                                        <div class="col-lg-12 no-pdd">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="sn-field mb-0 w-100">
                                                    <input type="password" name="password"
                                                        placeholder="{{ __('forms.passwordPlaceholder') }}">
                                                    <i>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                                                        </svg>
                                                    </i>
                                                </div>
                                                <div style="margin-left: 10px; flex-shrink: 0;">
                                                    <span data-toggle="tooltip" data-placement="top"
                                                        title="আপনার পাসওয়ার্ড দিন" style="cursor: pointer; background: #e44d3a; color: white; padding: 2px 8px; border-radius: 50%; font-size: 14px; display: inline-block;">?</span>
                                                </div>
                                            </div>
                                            @error('password')
                                                <div class="text-danger error">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- 8. Confirm Password -->
                                        <div class="col-lg-12 no-pdd">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="sn-field mb-0 w-100">
                                                    <input type="password" name="password_confirmation"
                                                        placeholder="{{ __('forms.confirmPasswordPlaceholder') }}">
                                                    <i>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                                                        </svg>
                                                    </i>
                                                </div>
                                                <div style="margin-left: 10px; flex-shrink: 0;">
                                                    <span data-toggle="tooltip" data-placement="top"
                                                        title="পাসওয়ার্ড নিশ্চিত করুন" style="cursor: pointer; background: #e44d3a; color: white; padding: 2px 8px; border-radius: 50%; font-size: 14px; display: inline-block;">?</span>
                                                </div>
                                            </div>
                                            @error('password_confirmation')
                                                <div class="text-danger error">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-12 no-pdd">
                                            <div class="checky-sec st2">
                                                <div class="fgt-sec">
                                                    <input type="hidden" name="type" value="customer">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 no-pdd">
                                            <button type="submit" value="submit">{{ __('buttons.signUp') }}</button>
                                        </div>
                                    </div>
                                </form>

                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                <script>
                                    $(document).ready(function() {
                                        $('#customer_type_select').on('change', function() {
                                            var val = $(this).val();
                                            if (val === 'buy_earn') {
                                                $('#account_number_wrapper').show();
                                                $('#referral_code_wrapper').show();
                                                $('#referral_code_2_wrapper').show();
                                            } else if (val === 'direct_selling') {
                                                $('#account_number_wrapper').hide();
                                                $('select[name="account_number"]').val('');
                                                $('#referral_code_wrapper').hide();
                                                $('input[name="referral_code"]').val('');
                                                $('#referral_code_2_wrapper').hide();
                                                $('input[name="referral_code_2"]').val('');
                                            } else {
                                                $('#account_number_wrapper').hide();
                                                $('select[name="account_number"]').val('');
                                                $('#referral_code_wrapper').show();
                                                $('#referral_code_2_wrapper').show();
                                            }
                                        });
                                    });
                                </script>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footy-sec">
            <div class="container">
                <ul>
                    <li><a href="" title="">{{ __('menu.help') }}</a></li>
                    <li><a href="{{ route('website.about') }}" title="">{{ __('menu.about') }}</a></li>
                    <li><a href="#" title="">{{ __('menu.privacy') }}</a></li>
                    <li><a href="#" title="">{{ __('menu.terms') }}</a></li>
                </ul>

            </div>
        </div>
    </div>
@endsection
