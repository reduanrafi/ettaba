<?php

namespace Database\Seeders;

use App\Models\OnlineCategory;
use App\Models\Reward;
use App\Models\RewardSetting;
use Illuminate\Database\Seeder;

class RewardSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Online Categories
        $categories = [
            ['name' => 'গ্রোসারি', 'icon' => 'fas fa-shopping-basket', 'sort_order' => 1],
            ['name' => 'কাঁচা বাজার', 'icon' => 'fas fa-carrot', 'sort_order' => 2],
            ['name' => 'মাছ-মাংস', 'icon' => 'fas fa-fish', 'sort_order' => 3],
            ['name' => 'খাবার ও রেস্টুরেন্ট', 'icon' => 'fas fa-utensils', 'sort_order' => 4],
            ['name' => 'রিচার্জ, ডাটা ও মিনিট', 'icon' => 'fas fa-mobile-alt', 'sort_order' => 5],
            ['name' => 'ঔষধ ও ফার্মেসী', 'icon' => 'fas fa-pills', 'sort_order' => 6],
            ['name' => 'অর্গানিক পণ্য', 'icon' => 'fas fa-seedling', 'sort_order' => 7],
            ['name' => 'ফ্যাশন', 'icon' => 'fas fa-tshirt', 'sort_order' => 8],
            ['name' => 'ইলেক্ট্রনিক', 'icon' => 'fas fa-tv', 'sort_order' => 9],
            ['name' => 'মোবাইল', 'icon' => 'fas fa-mobile', 'sort_order' => 10],
            ['name' => 'হস্তশিল্প', 'icon' => 'fas fa-paint-brush', 'sort_order' => 11],
            ['name' => 'আরো দেখুন', 'icon' => 'fas fa-th-large', 'sort_order' => 12],
        ];

        foreach ($categories as $cat) {
            OnlineCategory::updateOrCreate(
                ['name' => $cat['name']],
                [
                    'icon' => $cat['icon'],
                    'sort_order' => $cat['sort_order'],
                    'is_visible' => 1,
                ]
            );
        }

        // 2. Settings (Motivational Message, Terms, Points Guide)
        $motivationalMsg = 'আপনি তো প্রতিদিনই কেনাকাটা করছেন বা খরচ করছেন। এবার সেই খরচ থেকেই পুরস্কারের পথে এগিয়ে যান! ইত্তেবা অ্যাপ বা ইত্তেবা’র লোকাল মার্চেন্ট থেকে নিয়মিত কেনাকাটা ও সার্ভিস গ্রহণ করুন, ক্যাশব্যাকের পাশাপাশি পয়েন্ট সংগ্রহ করুন এবং একে এক আনলক করুন তালিকায় উল্লেখিত দারুণসব পুরস্কার।';
        RewardSetting::setVal('motivational_message', $motivationalMsg);

        $howToEarn = [
            '🛒 ইত্তেবা অ্যাপ থেকে কেনাকাটা করুন',
            '🏪 ইত্তেবা Merchant থেকে কেনাকাটা করুন',
            '🎯 নির্দিষ্ট Campaign-এ অংশ নিন',
            '💳 প্রয়োজনীয় পণ্য ও Service গ্রহণ করুন',
            '⭐ নিয়মিত কেনাকাটার মাধ্যমে Point সংগ্রহ করুন'
        ];
        RewardSetting::setVal('how_to_earn_points', $howToEarn);

        $terms = "পুরস্কার সম্পর্কিত নিয়ম ও শর্তাবলি:\n১. অর্জিত পয়েন্ট নির্দিষ্ট মেয়াদের মধ্যে ব্যবহারযোগ্য।\n২. পুরস্কারের জন্য আবেদনের পূর্বে এনআইডি (NID) ভেরিফিকেশন সহ প্রোফাইল সম্পূর্ণ আপডেট থাকতে হবে।\n৩. ব্যবহারকারীর আবেদন এডমিন যাচাই-বাছাই করে অনুমোদন (Accept) করলে স্বয়ংক্রিয়ভাবে পয়েন্ট কাটা হবে।\n৪. আবেদন বাতিল বা রিজেক্ট হলে কোনো পয়েন্ট কাটা হবে না।\n৫. এডমিনের নোট ও উল্লেখিত নির্দিষ্ট তারিখ, সময় এবং অফিস ঠিকানায় উপস্থিত হয়ে পুরস্কার গ্রহণ করতে হবে।";
        RewardSetting::setVal('terms_and_conditions', $terms);

        // 3. Customer Rewards
        $customerRewards = [
            [
                'account_type' => 'customer',
                'level_no' => 1,
                'title' => 'Ettaba Welcome Gift',
                'subtitle' => 'টি-শার্ট ও স্টাইলিশ ক্যাপ গিফট প্যাক',
                'reward_name' => 'টি-শার্ট ও ক্যাপ গিফট প্যাক',
                'description' => 'ইত্তেবা প্রিমিয়াম ব্র্যান্ডেড কোয়ালিটি টি-শার্ট ও আকর্ষণীয় ক্যাপের আকর্ষণীয় কম্বো প্যাক।',
                'required_points' => 300,
                'sort_order' => 1,
                'info_title' => 'পুরস্কার সম্পর্কিত তথ্য:',
                'info_items' => "১০০% প্রিমিয়াম সুতি ফেব্রিক\nসাইজ পছন্দের সুযোগ\nইত্তেবা অফিস থেকে সরাসরি ডেলিভারি বা কুরিয়ার সুবিধা",
                'howto_title' => 'কীভাবে Point অর্জন করবেন?',
                'howto_items' => "ইত্তেবা অ্যাপে নিয়মিত অর্ডার করুন\nনিকটস্থ মার্চেন্ট শপ থেকে কেনাকাটা করুন\nক্যাশব্যাকের পাশাপাশি প্রতি ক্রয়ে পয়েন্ট অর্জন করুন",
            ],
            [
                'account_type' => 'customer',
                'level_no' => 2,
                'title' => 'Ettaba Smart Shopper',
                'subtitle' => 'স্মার্ট ওয়াচ / ব্লুটুথ ইয়ারবাডস',
                'reward_name' => 'স্মার্ট ওয়াচ / ব্লুটুথ ইয়ারবাডস',
                'description' => 'দৈনন্দিন লাইফস্টাইল সহজ করতে প্রিমিয়াম ফিচারযুক্ত স্মার্ট ওয়াচ অথবা ওয়াটারপ্রুফ ব্লুটুথ ইয়ারবাডস।',
                'required_points' => 600,
                'sort_order' => 2,
                'info_title' => 'পুরস্কার সম্পর্কিত তথ্য:',
                'info_items' => "অরিজিনাল ওয়ারেন্টি সহ ব্রান্ডেড গ্যাজেট\nহৃদস্পন্দন ও ফিটনেস ট্র্যাকিং ফিচার\nকিশোরগঞ্জ ইত্তেবা প্রধান কার্যালয় হতে সংগ্রহযোগ্য",
                'howto_title' => 'কীভাবে Point অর্জন করবেন?',
                'howto_items' => "প্রতিদিনের প্রয়োজনীয় বাজার সম্পন্ন করুন\nইত্তেবা শপে নতুন অফার ও মেগা ডিলে অংশ নিন",
            ],
            [
                'account_type' => 'customer',
                'level_no' => 3,
                'title' => 'Ettaba Selected Customer',
                'subtitle' => '১ দিনের ডে ট্যুর প্যাকেজ',
                'reward_name' => '1-Day Local Tour',
                'description' => 'ইত্তেবার সাথে অসাধারণ ১ দিনের ডে-আউট ট্যুর ও লাক্সারি লাঞ্চ প্যাকেজ।',
                'required_points' => 1000,
                'sort_order' => 3,
                'info_title' => 'পুরস্কার সম্পর্কিত তথ্য:',
                'info_items' => "আরামদায়ক ট্রান্সপোর্ট ব্যবস্থা\nঅভিজ্ঞ গাইড ও লাক্সারি লাঞ্চ\nআকর্ষণীয় ফটোসেশন ও গিফট হ্যাম্পার",
                'howto_title' => 'কীভাবে Point অর্জন করবেন?',
                'howto_items' => "মার্চেন্ট আউটলেটে QR কোড স্ক্যান করে পে করুন\nবন্ধুদের রেফার করে অতিরিক্ত পয়েন্ট পান",
            ],
            [
                'account_type' => 'customer',
                'level_no' => 4,
                'title' => 'Ettaba Kitchen Master',
                'subtitle' => 'ইলেকট্রিক কেটলি ও ডিনার সেট',
                'reward_name' => 'ইলেকট্রিক কেটলি ও ডিনার সেট',
                'description' => 'কিচেনের সৌন্দর্য ও সুবিধা বৃদ্ধিতে আকর্ষণীয় সিরামিক ডিনার সেট এবং ফাস্ট-বয়েলিং কেটলি।',
                'required_points' => 1800,
                'sort_order' => 4,
                'info_title' => 'পুরস্কার সম্পর্কিত তথ্য:',
                'info_items' => "ফুড গ্রেড স্টেইনলেস স্টিল কেটলি\n২৪ পিসের আকর্ষণীয় ডিনার সেট",
                'howto_title' => 'কীভাবে Point অর্জন করবেন?',
                'howto_items' => "অনলাইন গ্রোসারি ও গৃহস্থালী পণ্য অর্ডার করুন",
            ],
            [
                'account_type' => 'customer',
                'level_no' => 5,
                'title' => 'Ettaba Silver Customer',
                'subtitle' => 'মাল্টি-ফাংশন ব্লেন্ডার ও জুসার',
                'reward_name' => 'মাল্টি-ফাংশন ব্লেন্ডার ও জুসার',
                'description' => 'হেভি ডিউটি মোটরযুক্ত মাল্টি-ফাংশন ব্লেন্ডার গ্রাইন্ডার ও জুসার মেশিন।',
                'required_points' => 2400,
                'sort_order' => 5,
                'info_title' => 'পুরস্কার সম্পর্কিত তথ্য:',
                'info_items' => "শক্তিশালী ৭৫০ ওয়াট কপার মোটর\n৩টি ভিন্ন সাইজের স্টেইনলেস স্টিল জার\n১ বছরের অফিসিয়াল ব্র্যান্ড ওয়ারেন্টি",
                'howto_title' => 'কীভাবে Point অর্জন করবেন?',
                'howto_items' => "ইত্তেবা অ্যাপ থেকে নিয়মিত পারিবারিক কেনাকাটা করুন",
            ],
            [
                'account_type' => 'customer',
                'level_no' => 6,
                'title' => 'Ettaba Gold Customer',
                'subtitle' => 'মাইক্রোওয়েভ ওভেন / এয়ার কুলার',
                'reward_name' => 'মাইক্রোওয়েভ ওভেন',
                'description' => 'সহজেই খাবার গরম ও বেকিং করতে আধুনিক স্মার্ট মাইক্রোওয়েভ ওভেন।',
                'required_points' => 2800,
                'sort_order' => 6,
                'info_title' => 'পুরস্কার সম্পর্কিত তথ্য:',
                'info_items' => "ডিজিটাল কন্ট্রোল প্যানেল\nঅটোমেটিক ডিফ্রস্ট ও দ্রুত গরম করার প্রযুক্তি",
                'howto_title' => 'কীভাবে Point অর্জন করবেন?',
                'howto_items' => "ইত্তেবার সব সার্ভিসের সাথে সক্রিয় থাকুন",
            ],
            [
                'account_type' => 'customer',
                'level_no' => 7,
                'title' => 'Ettaba Premium Customer',
                'subtitle' => 'স্মার্ট রাইস কুকার',
                'reward_name' => 'রাইস কুকার (Rice Cooker)',
                'description' => 'অটোমেটিক কিপ-ওয়ার্ম সুবিধাযুক্ত প্রিমিয়াম কোয়ালিটি ডাবল পট রাইস কুকার। প্রতিদিনের রান্নাকে করবে নির্ঝঞ্ঝাট।',
                'required_points' => 3000,
                'sort_order' => 7,
                'info_title' => 'পুরস্কার সম্পর্কিত তথ্য:',
                'info_items' => "২.৮ লিটার ধারণক্ষমতা\nনন-স্টিক ডাবল পট ও স্টিমার\nঅরিজিনাল ১ বছর সার্ভিস ওয়ারেন্টি",
                'howto_title' => 'কীভাবে Point অর্জন করবেন?',
                'howto_items' => "🛒 ইত্তেবা অ্যাপ থেকে কেনাকাটা করুন\n🏪 ইত্তেবা Merchant থেকে কেনাকাটা করুন\n🎯 নির্দিষ্ট Campaign-এ অংশ নিন",
            ],
            [
                'account_type' => 'customer',
                'level_no' => 8,
                'title' => 'Ettaba VIP Customer',
                'subtitle' => 'লেটেস্ট স্মার্টফোন (Android 5G)',
                'reward_name' => 'লেটেস্ট স্মার্টফোন',
                'description' => 'উন্নত ক্যামেরা, বড় ডিসপ্লে এবং দীর্ঘস্থায়ী ব্যাটারি ব্যাকআপযুক্ত ব্রান্ড নিউ ৫জি স্মার্টফোন।',
                'required_points' => 5000,
                'sort_order' => 8,
                'info_title' => 'পুরস্কার সম্পর্কিত তথ্য:',
                'info_items' => "অফিসিয়াল বিটিআরসি অনুমোদিত হ্যান্ডসেট\nফুল বক্স উইথ অরিজিনাল এক্সেসরিজ\n১ বছর অফিসিয়াল ব্র্যান্ড ওয়ারেন্টি",
                'howto_title' => 'কীভাবে Point অর্জন করবেন?',
                'howto_items' => "প্রতিদিনের খরচ থেকে পয়েন্ট জমিয়ে এই মেগা পুরস্কারটি আনলক করুন",
            ],
        ];

        foreach ($customerRewards as $r) {
            Reward::updateOrCreate(
                ['account_type' => $r['account_type'], 'level_no' => $r['level_no']],
                $r
            );
        }

        // 4. Partner Rewards
        $partnerRewards = [
            [
                'account_type' => 'partner',
                'level_no' => 1,
                'title' => 'Ettaba Bronze Partner',
                'subtitle' => 'অফিসিয়াল সার্টিফিকেট ও ব্র্যান্ডিং প্যাক',
                'reward_name' => 'ব্র্যান্ডিং গিফট প্যাক',
                'description' => 'পার্টনার সম্মাননা পত্র, আইডি কিট ও ব্র্যান্ডিং প্রমোশন প্যাক।',
                'required_points' => 1000,
                'sort_order' => 1,
                'info_title' => 'পুরস্কার সম্পর্কিত তথ্য:',
                'info_items' => "পার্টনারশিপ অফিশিয়াল ক্রেস্ট\nপ্রিমিয়াম ব্র্যান্ডিং আইডি কার্ড ও টি-শার্ট",
                'howto_title' => 'কীভাবে Point অর্জন করবেন?',
                'howto_items' => "নতুন কাস্টমার ও রেফারেল টিম তৈরি করুন",
            ],
            [
                'account_type' => 'partner',
                'level_no' => 2,
                'title' => 'Ettaba Silver Partner',
                'subtitle' => 'স্মার্টফোন বা কক্সবাজার ট্যুর',
                'reward_name' => 'কক্সবাজার ৩ দিন ২ রাত কাপল ট্যুর',
                'description' => 'ফাইভ স্টার রিসোর্টে থাকা, বিমান টিকিট বা লাক্সারি বাস সহ কাপল ট্যুর প্যাকেজ।',
                'required_points' => 3000,
                'sort_order' => 2,
                'info_title' => 'পুরস্কার সম্পর্কিত তথ্য:',
                'info_items' => "থাকা, খাওয়া ও যাতায়াত সম্পূর্ণ ফ্রি\nলাক্সারি হোটেল রিসোর্ট",
                'howto_title' => 'কীভাবে Point অর্জন করবেন?',
                'howto_items' => "টিম পারফরম্যান্স ও সক্রিয় রেফারেলের মাধ্যমে দ্রুত পয়েন্ট বৃদ্ধি করুন",
            ],
            [
                'account_type' => 'partner',
                'level_no' => 3,
                'title' => 'Ettaba Gold Partner',
                'subtitle' => 'কোর আই-৫ ল্যাপটপ অথবা মেগা গিফট',
                'reward_name' => 'Core i5 Laptop',
                'description' => 'ব্যবসায়িক ও ফ্রিল্যান্সিং কাজ দ্রুত পরিচালনার জন্য ব্র্যান্ড নিউ কোর আই-৫ ল্যাপটপ।',
                'required_points' => 6000,
                'sort_order' => 3,
                'info_title' => 'পুরস্কার সম্পর্কিত তথ্য:',
                'info_items' => "১৬ জিবি র‍্যাম ও ৫১২ জিবি এসএসডি\n২ বছর ফুল অফিসিয়াল ওয়ারেন্টি",
                'howto_title' => 'কীভাবে Point অর্জন করবেন?',
                'howto_items' => "বড় ভলিউমের সেলস ও নেটওয়ার্ক বিস্তার করুন",
            ],
        ];

        foreach ($partnerRewards as $r) {
            Reward::updateOrCreate(
                ['account_type' => $r['account_type'], 'level_no' => $r['level_no']],
                $r
            );
        }

        // 5. Merchant Rewards
        $merchantRewards = [
            [
                'account_type' => 'merchant',
                'level_no' => 1,
                'title' => 'Ettaba Rising Merchant',
                'subtitle' => 'শপ ব্র্যান্ডিং সাইনবোর্ড ও সার্টিফিকেট',
                'reward_name' => 'ব্র্যান্ডিং সাইনবোর্ড কিট',
                'description' => 'আপনার দোকানের জন্য আকর্ষণীয় লাইটিং সাইনবোর্ড ও অফিশিয়াল অথরাইজড পার্টনার ক্রেস্ট।',
                'required_points' => 1000,
                'sort_order' => 1,
                'info_title' => 'পুরস্কার সম্পর্কিত তথ্য:',
                'info_items' => "দোকানের আকৃতি অনুযায়ী সাইনবোর্ড প্রিন্টিং\nঅফিসিয়াল অথরাইজড স্টোর স্টিকার ও ব্যানার",
                'howto_title' => 'কীভাবে Point অর্জন করবেন?',
                'howto_items' => "কাস্টমারদের হ্যান্ড ক্যাশ পেমেন্টে ইত্তেবা পয়েন্ট ও ক্যাশব্যাক প্রদান করুন",
            ],
            [
                'account_type' => 'merchant',
                'level_no' => 2,
                'title' => 'Ettaba Star Merchant',
                'subtitle' => 'ডিজিটাল পিওএস প্রিন্টার / অ্যান্ড্রয়েড ট্যাব',
                'reward_name' => 'ডিজিটাল পিওএস রসিদ প্রিন্টার',
                'description' => 'দোকানের ক্যাশ কাউন্টারের দ্রুত ইনভয়েস প্রিন্ট করার জন্য ব্লুটুথ থার্মাল পিওএস প্রিন্টার।',
                'required_points' => 3000,
                'sort_order' => 2,
                'info_title' => 'পুরস্কার সম্পর্কিত তথ্য:',
                'info_items' => "মোবাইল ও পিসি উভয়ের সাথে ব্লুটুথ সংযোগযোগ্য\nরোল পেপার সহ কমপ্লিট সেট",
                'howto_title' => 'কীভাবে Point অর্জন করবেন?',
                'howto_items' => "প্রতিদিন আরও বেশি কাস্টমারকে ইত্তেবা পেমেন্ট গ্রহণে উৎসাহিত করুন",
            ],
            [
                'account_type' => 'merchant',
                'level_no' => 3,
                'title' => 'Ettaba Champion Merchant',
                'subtitle' => '৪৩ ইঞ্চি স্মার্ট এলইডি টিভি',
                'reward_name' => '43-Inch Smart LED TV',
                'description' => 'শপ বা বাসাবাড়ির জন্য ৪কে আল্ট্রা এইচডি ৪৩ ইঞ্চি ভয়েস কন্ট্রোল স্মার্ট অ্যান্ড্রয়েড টিভি।',
                'required_points' => 6000,
                'sort_order' => 3,
                'info_title' => 'পুরস্কার সম্পর্কিত তথ্য:',
                'info_items' => "ভয়েস রিমোট ও গুগল প্লে স্টোর সাপোর্ট\n২ বছর প্যানেল রিপ্লেসমেন্ট গ্যারান্টি",
                'howto_title' => 'কীভাবে Point অর্জন করবেন?',
                'howto_items' => "হ্যান্ড ক্যাশ ক্যাশব্যাক সেলে মাসিক টার্গেট পূরণ করুন",
            ],
        ];

        foreach ($merchantRewards as $r) {
            Reward::updateOrCreate(
                ['account_type' => $r['account_type'], 'level_no' => $r['level_no']],
                $r
            );
        }
    }
}
