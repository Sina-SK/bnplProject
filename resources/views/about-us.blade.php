<!DOCTYPE html>
<html lang="fa" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قسطی‌پی | سیستم خرید اعتباری و اقساطی BNPL</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts for Persian Typography (Vazirmatn) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Vazirmatn', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        },
                        secondary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                        },
                        neutralCustom: {
                            100: '#f3f4f6',
                            800: '#1f2937',
                            900: '#111827',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: #f8fafc;
        }
    </style>

    <!-- ALPINE APP SCRIPTS (Defined before Alpine.js executes) -->
    <script>
        function shopApp() {
            return {
                page: 'home',
                calculatorValue: 12000000,
                calcMonths: 4,
                
                // Active Product details simulation
                selectedProduct: {},
                productMonths: 4,

                // Registration simulation variables
                registerStep: 1,
                regPhone: '',
                regNationalId: '',
                regName: '',
                nationalCardUploaded: false,
                creditChecking: false,
                
                // Contact Form State
                contactName: '',
                contactPhone: '',
                contactSubject: '',
                contactMessage: '',
                contactSubmitting: false,
                
                // Auth States
                isLoggedIn: false,
                userPhone: '',
                userApproved: false,
                creditLimit: 25000000,

                // Shopping Cart State
                cart: [],
                cartOpen: false,

                // Notification Toast List
                toasts: [],

                // Dummy Products DB
                products: [
                    {
                        id: 1,
                        name: "گوشی موبایل آیفون ۱۵ پرو مکس ۲۵۶ گیگابایت",
                        desc: "نسخه گلوبال، سنسورهای بروز شده، طراحی تیتانیومی بی‌نظیر به همراه گارانتی رسمی شرکت پارس",
                        price: 64000000,
                        image: "https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=500&auto=format&fit=crop&q=80",
                        icon: "fa-solid fa-mobile-screen-button text-rose-500"
                    },
                    {
                        id: 2,
                        name: "مک‌بوک ایر ۱۳ اینچی M3 اپل",
                        desc: "پردازنده فوق‌العاده سریع M3، رم ۸ گیگابایت و حافظه پرسرعت SSD ۲۵۶ گیگابایتی برای کارهای حرفه‌ای",
                        price: 52000000,
                        image: "https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=500&auto=format&fit=crop&q=80",
                        icon: "fa-solid fa-laptop text-indigo-500"
                    },
                    {
                        id: 3,
                        name: "کنسول بازی پلی‌استیشن ۵ نسخه استاندارد اسلیم",
                        desc: "آخرین مدل کنسول سونی، طراحی شیک‌تر و باریک‌تر به همراه ظرفیت ۱ ترابایت کامل برای بازی‌ها",
                        price: 28500000,
                        image: "https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=500&auto=format&fit=crop&q=80",
                        icon: "fa-solid fa-gamepad text-slate-700"
                    },
                    {
                        id: 4,
                        name: "هدفون بی‌سیم نویز کنسلینگ سونی مدل WH-1000XM5",
                        desc: "کیفیت صدای استودیویی، تفکیک صدای برتر بازار و عمر باتری بی‌نظیر ۳۰ ساعته برای شنوندگان خلاق",
                        price: 16200000,
                        image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop&q=80",
                        icon: "fa-solid fa-headphones text-amber-500"
                    }
                ],

                init() {
                    // Start with first product by default for detailing
                    this.selectedProduct = this.products[0];
                },

                setPage(pageName) {
                    this.page = pageName;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                // Utility format price
                formatPrice(value) {
                    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                },

                // Custom notification toast generator
                showToast(message, type = 'success') {
                    const id = Date.now();
                    this.toasts.push({ id, message, type });
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 4000);
                },

                // Calculate plans for live dynamic widgets
                calculatePlanPrice() {
                    let principal = parseFloat(this.calculatorValue);
                    let months = parseInt(this.calcMonths);
                    let totalAmount = principal;
                    
                    if (months === 6) {
                        // Apply mock 5% interest rate for longer duration
                        totalAmount = principal * 1.05;
                    }

                    let installment = totalAmount / months;
                    let downpayment = totalAmount / months; // In standard BNPL first instalment is paid today.

                    return {
                        total: totalAmount,
                        installment: installment,
                        downpayment: downpayment
                    };
                },

                viewProduct(item) {
                    this.selectedProduct = item;
                    this.productMonths = 4;
                    this.setPage('product');
                },

                addToCart(product, planMonths) {
                    this.cart.push({
                        product: product,
                        months: planMonths
                    });
                    this.showToast(`محصول ${product.name} در طرح اقساطی ${planMonths} ماهه به سبد خرید اضافه شد.`, 'success');
                    this.cartOpen = true;
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    this.showToast('محصول از سبد خرید حذف شد.', 'info');
                },

                getCartTotal() {
                    return this.cart.reduce((sum, item) => sum + item.product.price, 0);
                },

                getCartDownpayment() {
                    return this.cart.reduce((sum, item) => {
                        let price = item.product.price;
                        if (item.months === 6) price = price * 1.05;
                        return sum + (price / item.months);
                    }, 0);
                },

                toggleCart() {
                    this.cartOpen = !this.cartOpen;
                },

                // Simulated Step-by-Step Registration
                submitStepOne() {
                    if (!this.regPhone || !this.regNationalId || !this.regName) {
                        this.showToast('لطفا تمامی فیلدهای الزامی را به درستی پر کنید.', 'error');
                        return;
                    }
                    this.registerStep = 2;
                    this.showToast('کد فعال‌سازی تستی فرستاده شد. اطلاعات تایید هویت با موفقیت ثبت گردید.', 'success');
                },

                submitStepTwo() {
                    if (!this.nationalCardUploaded) {
                        this.showToast('لطفا فایل تصویر کارت ملی را آپلود نمایید.', 'error');
                        return;
                    }
                    
                    this.registerStep = 3;
                    this.creditChecking = true;

                    // Simulation of credit API check (Bank records check)
                    setTimeout(() => {
                        this.creditChecking = false;
                        this.userApproved = true;
                        this.isLoggedIn = true;
                        this.userPhone = this.regPhone || '۰۹۱۲۳۴۵۶۷۸۹';
                        this.showToast('تبریک! فرآیند اعتبار سنجی موفقیت‌آمیز بود.', 'success');
                    }, 5000);
                },

                completeRegistration() {
                    this.setPage('home');
                    this.showToast(`میزان ${this.formatPrice(this.creditLimit)} تومان اعتبار فعال شد. هم‌اکنون می‌توانید خرید کنید!`, 'success');
                },

                // Contact form submit simulator
                submitContactForm() {
                    if (!this.contactName || !this.contactPhone || !this.contactMessage) {
                        this.showToast('لطفا فیلدهای ستاره‌دار (نام، شماره تماس و پیام) را تکمیل کنید.', 'error');
                        return;
                    }
                    this.contactSubmitting = true;
                    setTimeout(() => {
                        this.contactSubmitting = false;
                        this.showToast('پیام شما با موفقیت ثبت شد! کارشناسان ما به زودی با شما تماس خواهند گرفت.', 'success');
                        this.contactName = '';
                        this.contactPhone = '';
                        this.contactSubject = '';
                        this.contactMessage = '';
                        this.setPage('home');
                    }, 1500);
                },

                // Simulated Checkout BNPL processor
                processCheckout() {
                    if (!this.isLoggedIn || !this.userApproved) {
                        this.showToast('ابتدا باید ثبت‌نام کرده و اعتبار BNPL خود را فعال کنید.', 'error');
                        this.setPage('register');
                        this.cartOpen = false;
                        return;
                    }

                    let total = this.getCartTotal();
                    if (total > this.creditLimit) {
                        this.showToast(`کل مبلغ خرید (${this.formatPrice(total)} تومان) از اعتبار فعال شما بیشتر است!`, 'error');
                        return;
                    }

                    // Success Simulation
                    this.creditLimit = this.creditLimit - total;
                    this.cart = [];
                    this.cartOpen = false;
                    this.showToast('سفارش اعتباری شما با موفقیت ثبت شد! اولین قسط کسر گردید و کالا آماده فرآیند ارسال است.', 'success');
                }
            }
        }
    </script>

    <!-- Alpine.js with defer attribute (Ensures shopApp is loaded first) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body x-data="shopApp()" class="text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- HEADER / NAVIGATION -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Right: Logo & Menu -->
                <div class="flex items-center gap-8">
                    <a href="#" @click.prevent="setPage('home')" class="flex items-center gap-2">
                        <span class="p-2.5 bg-gradient-to-tr from-primary-600 to-primary-500 text-white rounded-2xl shadow-md shadow-primary-500/20">
                            <i class="fa-solid fa-bolt text-xl"></i>
                        </span>
                        <div class="flex flex-col">
                            <span class="font-black text-xl text-slate-900 tracking-tight">قسطی‌پی</span>
                            <span class="text-[10px] text-slate-400 -mt-1 font-semibold">بخر، بعداً پرداخت کن!</span>
                        </div>
                    </a>

                    <!-- Desktop Menu -->
                    <nav class="hidden md:flex items-center gap-1">
                        <a href="#" @click.prevent="setPage('home')" :class="page === 'home' ? 'bg-primary-50 text-primary-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all">صفحه اصلی</a>
                        <a href="#" @click.prevent="setPage('product')" :class="page === 'product' ? 'bg-primary-50 text-primary-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all">فروشگاه اعتباری</a>
                        <a href="#" @click.prevent="setPage('about')" :class="page === 'about' ? 'bg-primary-50 text-primary-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all">درباره ما</a>
                        <a href="#" @click.prevent="setPage('contact')" :class="page === 'contact' ? 'bg-primary-50 text-primary-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all">ارتباط با ما</a>
                    </nav>
                </div>

                <!-- Left: Quick Actions -->
                <div class="flex items-center gap-4">
                    <!-- Wallet Limit Badge -->
                    <div x-show="userApproved" class="hidden sm:flex items-center gap-2 bg-emerald-50 text-emerald-700 px-4 py-2 rounded-xl border border-emerald-100 animate-pulse">
                        <i class="fa-solid fa-wallet text-sm"></i>
                        <span class="text-xs font-bold">اعتبار فعال شما:</span>
                        <span class="text-sm font-black" x-text="formatPrice(creditLimit) + ' تومان'"></span>
                    </div>

                    <!-- Cart Link -->
                    <button @click="toggleCart()" class="relative p-2.5 text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                        <i class="fa-solid fa-shopping-basket text-xl"></i>
                        <span x-show="cart.length > 0" x-text="cart.length" class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white animate-bounce"></span>
                    </button>

                    <!-- User Panel Button / Register -->
                    <template x-if="!isLoggedIn">
                        <button @click="setPage('register')" class="flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-primary-500/10 transition-all">
                            <i class="fa-solid fa-user-plus"></i>
                            <span>دریافت فوری اعتبار (رایگان)</span>
                        </button>
                    </template>
                    <template x-if="isLoggedIn">
                        <div class="flex items-center gap-2 bg-slate-100 px-4 py-2.5 rounded-xl border border-slate-200">
                            <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span>
                            <span class="text-xs font-bold text-slate-700" x-text="userPhone"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </header>

    <!-- CONTENT WRAPPER -->
    <main class="flex-grow">
        
        <!-- PAGE 1: HOME PAGE -->
        <div x-show="page === 'home'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <!-- Hero Banner & Concept Explanation -->
            <section class="relative overflow-hidden py-16 lg:py-24 bg-gradient-to-b from-slate-50 to-white">
                <div class="absolute inset-0 bg-[radial-gradient(#e0e7ff_1px,transparent_1px)] [background-size:16px_16px] opacity-40"></div>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                        <!-- Hero Info -->
                        <div class="lg:col-span-7 space-y-8 text-center lg:text-right">
                            <div class="inline-flex items-center gap-2 bg-primary-50 text-primary-700 px-4 py-1.5 rounded-full border border-primary-100/50">
                                <span class="flex h-2 w-2 relative">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                                </span>
                                <span class="text-xs font-bold">بزرگترین جشنواره خرید قسطی بدون چک و ضامن</span>
                            </div>

                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 leading-tight">
                                امروز بخرید، <br>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-indigo-500">طی ۴ قسط بدون کارمزد</span> پرداخت کنید!
                            </h1>

                            <p class="text-slate-600 text-lg leading-relaxed max-w-2xl mx-auto lg:mx-0">
                                با سیستم هوشمند BNPL قسطی‌پی، بدون نیاز به پیش‌پرداخت، ضامن، سفته یا چک صیادی، کالای دیجیتال یا لوازم خانگی مورد نیاز خود را همین حالا سفارش دهید و هزینه‌اش را در اقساط ساده پرداخت کنید.
                            </p>

                            <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                                <button @click="setPage('register')" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-8 py-4 rounded-2xl text-base font-bold shadow-xl shadow-primary-600/20 hover:scale-[1.02] transition-all">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                                    <span>دریافت ۳۰ میلیون تومان اعتبار آنی</span>
                                </button>
                                <button @click="setPage('product')" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-8 py-4 rounded-2xl text-base font-bold transition-all">
                                    <i class="fa-solid fa-store text-slate-400"></i>
                                    <span>مشاهده محصولات اعتباری</span>
                                </button>
                            </div>

                            <!-- Trust Badges -->
                            <div class="grid grid-cols-3 gap-4 pt-4 border-t border-slate-100 max-w-md mx-auto lg:mx-0">
                                <div class="text-center lg:text-right">
                                    <div class="text-lg font-black text-slate-900">بدون بهره</div>
                                    <div class="text-xs text-slate-400">اقساط ۴ ماهه منصفانه</div>
                                </div>
                                <div class="text-center lg:text-right border-r border-slate-100 pr-4">
                                    <div class="text-lg font-black text-slate-900">زیر ۵ دقیقه</div>
                                    <div class="text-xs text-slate-400">ثبت‌نام و احراز هویت تمام آنلاین</div>
                                </div>
                                <div class="text-center lg:text-right border-r border-slate-100 pr-4">
                                    <div class="text-lg font-black text-slate-900">بدون ضامن</div>
                                    <div class="text-xs text-slate-400">فقط بر اساس خوش‌حسابی شما</div>
                                </div>
                            </div>
                        </div>

                        <!-- Interactive Feature Graphic (Live Calculator Preview) -->
                        <div class="lg:col-span-5">
                            <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100 relative">
                                <div class="absolute -top-4 -right-4 bg-yellow-400 text-yellow-950 font-black text-xs px-4 py-2 rounded-2xl shadow-md rotate-6">
                                    <i class="fa-solid fa-fire mr-1"></i> ابزار محاسبه‌گر قسط
                                </div>

                                <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                                    <i class="fa-solid fa-calculator text-primary-500"></i>
                                    <span>شبیه‌ساز هوشمند اقساط BNPL</span>
                                </h3>

                                <div class="space-y-6">
                                    <!-- Range Slider -->
                                    <div>
                                        <div class="flex justify-between text-xs font-bold text-slate-500 mb-2">
                                            <span>مبلغ کل سبد خرید</span>
                                            <span class="text-primary-600 font-extrabold text-sm" x-text="formatPrice(calculatorValue) + ' تومان'"></span>
                                        </div>
                                        <input type="range" min="2000000" max="30000000" step="500000" x-model="calculatorValue" class="w-full accent-primary-600 h-2 bg-slate-100 rounded-lg appearance-none cursor-pointer">
                                        <div class="flex justify-between text-[10px] text-slate-400 mt-1">
                                            <span>۲ میلیون تومان</span>
                                            <span>۳۰ میلیون تومان</span>
                                        </div>
                                    </div>

                                    <!-- Plan Selection Tabs -->
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 mb-2">انتخاب بازه پرداخت</label>
                                        <div class="grid grid-cols-3 gap-2">
                                            <button @click="calcMonths = 2" :class="calcMonths === 2 ? 'bg-primary-600 text-white' : 'bg-slate-50 hover:bg-slate-100 text-slate-600'" class="py-2.5 rounded-xl text-xs font-bold transition-all">
                                                ۲ قسطه (بدون بهره)
                                            </button>
                                            <button @click="calcMonths = 4" :class="calcMonths === 4 ? 'bg-primary-600 text-white' : 'bg-slate-50 hover:bg-slate-100 text-slate-600'" class="py-2.5 rounded-xl text-xs font-bold transition-all">
                                                ۴ قسطه (بدون بهره)
                                            </button>
                                            <button @click="calcMonths = 6" :class="calcMonths === 6 ? 'bg-primary-600 text-white' : 'bg-slate-50 hover:bg-slate-100 text-slate-600'" class="py-2.5 rounded-xl text-xs font-bold transition-all">
                                                ۶ قسطه (کارمزد جزئی)
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Results Breakdown -->
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-3">
                                        <div class="flex justify-between items-center text-xs text-slate-500">
                                            <span>مبلغ هر قسط ماهانه:</span>
                                            <span class="text-slate-900 font-black text-sm" x-text="formatPrice(Math.round(calculatePlanPrice().installment)) + ' تومان'"></span>
                                        </div>
                                        <div class="flex justify-between items-center text-xs text-slate-500">
                                            <span>کارمزد ماهیانه:</span>
                                            <span class="text-slate-900 font-bold" x-text="calcMonths === 6 ? '۱.۸ درصد' : 'بدون کارمزد (۰٪)'"></span>
                                        </div>
                                        <div class="flex justify-between items-center text-xs text-slate-500 border-t border-slate-200/50 pt-2.5">
                                            <span>پیش‌پرداخت در زمان خرید:</span>
                                            <span class="text-emerald-600 font-black text-sm" x-text="formatPrice(Math.round(calculatePlanPrice().downpayment)) + ' تومان'"></span>
                                        </div>
                                    </div>

                                    <button @click="setPage('register')" class="w-full py-3 bg-gradient-to-r from-primary-600 to-indigo-600 text-white font-bold text-sm rounded-xl hover:shadow-lg hover:shadow-primary-600/20 transition-all">
                                        شروع فرآیند دریافت اعتبار <i class="fa-solid fa-arrow-left ml-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How It Works (امکانات و مراحل خرید BNPL) -->
            <section class="py-16 bg-white border-y border-slate-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                        <span class="text-xs font-bold text-primary-600 uppercase tracking-wider">ساده و سریع در ۴ مرحله</span>
                        <h2 class="text-3xl font-extrabold text-slate-900">چگونه با قسطی‌پی اعتباری خرید کنیم؟</h2>
                        <p class="text-slate-500 text-sm">پیچیدگی‌های سنتی دریافت وام را فراموش کنید. کل فرآیند در عرض کمتر از ۵ دقیقه انجام می‌شود.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <!-- Step 1 -->
                        <div class="relative bg-slate-50/50 p-6 rounded-2xl border border-slate-100 text-center space-y-4">
                            <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-xl flex items-center justify-center text-xl font-bold mx-auto">۱</div>
                            <h4 class="font-bold text-slate-900 text-base">ثبت نام سریع</h4>
                            <p class="text-slate-500 text-xs leading-relaxed">فقط با وارد کردن شماره موبایل به نام خودتان و اطلاعات اولیه، حساب کاربری خود را بسازید.</p>
                        </div>
                        <!-- Step 2 -->
                        <div class="relative bg-slate-50/50 p-6 rounded-2xl border border-slate-100 text-center space-y-4">
                            <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-xl flex items-center justify-center text-xl font-bold mx-auto">۲</div>
                            <h4 class="font-bold text-slate-900 text-base">اعتبارسنجی آنلاین</h4>
                            <p class="text-slate-500 text-xs leading-relaxed">سیستم به صورت خودکار رتبه بانکی شما را استعلام کرده و سقف اعتباری شما را مشخص می‌کند.</p>
                        </div>
                        <!-- Step 3 -->
                        <div class="relative bg-slate-50/50 p-6 rounded-2xl border border-slate-100 text-center space-y-4">
                            <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-xl flex items-center justify-center text-xl font-bold mx-auto">۳</div>
                            <h4 class="font-bold text-slate-900 text-base">انتخاب محصول و قسط</h4>
                            <p class="text-slate-500 text-xs leading-relaxed">کالای دلخواه خود را به سبد خرید اضافه کرده و نحوه تقسیط آن (۲، ۴ یا ۶ ماهه) را انتخاب کنید.</p>
                        </div>
                        <!-- Step 4 -->
                        <div class="relative bg-slate-50/50 p-6 rounded-2xl border border-slate-100 text-center space-y-4">
                            <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-xl flex items-center justify-center text-xl font-bold mx-auto">۴</div>
                            <h4 class="font-bold text-slate-900 text-base">دریافت و پرداخت</h4>
                            <p class="text-slate-500 text-xs leading-relaxed">کالا را بلافاصله دریافت کرده و پرداخت مابقی قسط‌ها را از ماه آینده آغاز کنید.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Featured Products Showcase -->
            <section class="py-16 bg-slate-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-12 gap-4">
                        <div class="text-right">
                            <h2 class="text-2xl font-extrabold text-slate-900">محصولات ویژه با پیش‌پرداخت صفر درصد</h2>
                            <p class="text-slate-500 text-xs mt-1">همین حالا انتخاب کنید و با اعتبار BNPL خود سفارش دهید.</p>
                        </div>
                        <button @click="setPage('product')" class="text-primary-600 hover:text-primary-700 font-bold text-xs flex items-center gap-1 bg-white px-4 py-2 rounded-xl border border-slate-200">
                            مشاهده تمام کالاها <i class="fa-solid fa-chevron-left text-[10px]"></i>
                        </button>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <template x-for="item in products" :key="item.id">
                            <div class="bg-white rounded-3xl border border-slate-100 p-5 shadow-sm hover:shadow-lg transition-all group flex flex-col justify-between">
                                <div>
                                    <!-- Beautiful Product Image with error fallback and sleek aspect ratio -->
                                    <div class="bg-slate-100 rounded-2xl h-48 overflow-hidden relative mb-4 group-hover:scale-95 transition-all">
                                        <img :src="item.image" :alt="item.name" onerror="this.onerror=null; this.src='https://placehold.co/500x400/eceff1/455a64?text=قسطی‌پـی'" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        <span class="absolute top-2.5 right-2.5 bg-primary-600 text-white font-extrabold text-[10px] px-2.5 py-1 rounded-lg">
                                            اقساط ۴ ماهه
                                        </span>
                                    </div>

                                    <h3 class="font-bold text-slate-800 text-sm mb-1 group-hover:text-primary-600 transition-all" x-text="item.name"></h3>
                                    <p class="text-[11px] text-slate-400 mb-4 line-clamp-1" x-text="item.desc"></p>
                                </div>

                                <div class="space-y-4 pt-3 border-t border-slate-50">
                                    <div class="flex justify-between items-baseline">
                                        <span class="text-[10px] text-slate-400 font-semibold">قیمت نقدی:</span>
                                        <span class="text-sm font-black text-slate-900" x-text="formatPrice(item.price) + ' تومان'"></span>
                                    </div>
                                    <!-- Monthly installments preview -->
                                    <div class="bg-primary-50/50 p-2.5 rounded-xl border border-primary-100/30 flex justify-between items-center">
                                        <span class="text-[10px] text-primary-700 font-bold">هر ماه فقط:</span>
                                        <span class="text-xs font-black text-primary-700" x-text="formatPrice(Math.round(item.price / 4)) + ' تومان'"></span>
                                    </div>

                                    <button @click="viewProduct(item)" class="w-full py-2.5 bg-slate-900 hover:bg-primary-600 text-white text-xs font-bold rounded-xl transition-all">
                                        جزئیات و خرید اقساطی
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </section>
        </div>

        <!-- PAGE 2: PRODUCT DETAILS -->
        <div x-show="page === 'product'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumbs -->
                <div class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-8">
                    <a href="#" @click.prevent="setPage('home')" class="hover:text-slate-600">خانه</a>
                    <i class="fa-solid fa-chevron-left text-[9px]"></i>
                    <span class="text-slate-700" x-text="selectedProduct.name"></span>
                </div>

                <!-- Product Display Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 bg-white rounded-3xl border border-slate-100 p-6 sm:p-10 shadow-sm">
                    <!-- Left: Images -->
                    <div class="lg:col-span-5 space-y-4">
                        <div class="bg-slate-50 border border-slate-100 rounded-3xl h-96 overflow-hidden relative shadow-inner">
                            <!-- Premium dynamic product image preview -->
                            <img :src="selectedProduct.image" :alt="selectedProduct.name" onerror="this.onerror=null; this.src='https://placehold.co/600x500/eceff1/455a64?text=قسطی‌پـی'" class="w-full h-full object-cover">
                            <span class="absolute bottom-4 right-4 bg-slate-950/70 text-white text-xs font-bold px-3 py-1.5 rounded-xl backdrop-blur">
                                [نمای واقعی کالا]
                            </span>
                        </div>
                        <div class="grid grid-cols-4 gap-3">
                            <div class="bg-slate-50 border-2 border-primary-500 rounded-xl p-0.5 overflow-hidden h-20 cursor-pointer">
                                <img :src="selectedProduct.image" onerror="this.onerror=null; this.src='https://placehold.co/150x150/eceff1/455a64'" class="w-full h-full object-cover rounded-lg">
                            </div>
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 flex items-center justify-center h-20 cursor-pointer opacity-60 hover:opacity-100 transition-all">
                                <i class="fa-solid fa-cube text-2xl text-slate-400"></i>
                            </div>
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 flex items-center justify-center h-20 cursor-pointer opacity-60 hover:opacity-100 transition-all">
                                <i class="fa-solid fa-microchip text-2xl text-slate-400"></i>
                            </div>
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 flex items-center justify-center h-20 cursor-pointer opacity-60 hover:opacity-100 transition-all">
                                <i class="fa-solid fa-circle-info text-2xl text-slate-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Specs & BNPL Configurator -->
                    <div class="lg:col-span-7 space-y-6">
                        <div>
                            <span class="bg-emerald-50 text-emerald-700 border border-emerald-200/50 font-bold text-[10px] px-3 py-1.5 rounded-lg">کالای آماده ارسال فوری</span>
                            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mt-3" x-text="selectedProduct.name"></h1>
                            <p class="text-slate-500 text-sm mt-2 leading-relaxed" x-text="selectedProduct.desc"></p>
                        </div>

                        <!-- Highlights -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 border-y border-slate-100 py-4">
                            <div class="flex items-center gap-2.5">
                                <i class="fa-solid fa-truck-fast text-slate-400 text-base"></i>
                                <div>
                                    <div class="text-xs font-bold text-slate-500">ارسال از فردا</div>
                                    <div class="text-[10px] text-slate-400">تحویل اکسپرس</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 border-r border-slate-100 pr-4">
                                <i class="fa-solid fa-shield-halved text-slate-400 text-base"></i>
                                <div>
                                    <div class="text-xs font-bold text-slate-500">گارانتی طلایی</div>
                                    <div class="text-[10px] text-slate-400">۱۸ ماهه شرکتی</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 border-r border-slate-100 pr-4">
                                <i class="fa-solid fa-award text-slate-400 text-base"></i>
                                <div>
                                    <div class="text-xs font-bold text-slate-500">اصالت کالا</div>
                                    <div class="text-[10px] text-slate-400">تضمین ۱۰۰٪ اصلی</div>
                                </div>
                            </div>
                        </div>

                        <!-- Interactive Installment selector inside product page -->
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100/50 space-y-6">
                            <div class="flex justify-between items-center">
                                <h3 class="text-sm font-bold text-slate-900">طرح اقساطی هوشمند قسطی‌پی:</h3>
                                <div class="text-right">
                                    <div class="text-xs text-slate-400">قیمت نهایی نقدی:</div>
                                    <div class="text-base font-black text-slate-950" x-text="formatPrice(selectedProduct.price) + ' تومان'"></div>
                                </div>
                            </div>

                            <!-- Plan Choice -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- Option 1 -->
                                <label @click="productMonths = 2" class="relative bg-white border cursor-pointer rounded-xl p-4 flex flex-col justify-between gap-2 hover:border-primary-500 transition-all" :class="productMonths === 2 ? 'border-primary-500 ring-2 ring-primary-500/10' : 'border-slate-200'">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs font-bold text-slate-800">طرح ۲ ماهه</span>
                                        <input type="radio" name="product_plan" :checked="productMonths === 2" class="accent-primary-600">
                                    </div>
                                    <div class="text-primary-600 font-black text-sm" x-text="formatPrice(Math.round(selectedProduct.price / 2)) + ' / قسط' "></div>
                                    <span class="text-[10px] text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-md self-start">بدون بهره ۰٪</span>
                                </label>

                                <!-- Option 2 -->
                                <label @click="productMonths = 4" class="relative bg-white border cursor-pointer rounded-xl p-4 flex flex-col justify-between gap-2 hover:border-primary-500 transition-all" :class="productMonths === 4 ? 'border-primary-500 ring-2 ring-primary-500/10' : 'border-slate-200'">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs font-bold text-slate-800">طرح ۴ ماهه</span>
                                        <input type="radio" name="product_plan" :checked="productMonths === 4" class="accent-primary-600">
                                    </div>
                                    <div class="text-primary-600 font-black text-sm" x-text="formatPrice(Math.round(selectedProduct.price / 4)) + ' / قسط' "></div>
                                    <span class="text-[10px] text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-md self-start">پیشنهاد ویژه</span>
                                </label>

                                <!-- Option 3 -->
                                <label @click="productMonths = 6" class="relative bg-white border cursor-pointer rounded-xl p-4 flex flex-col justify-between gap-2 hover:border-primary-500 transition-all" :class="productMonths === 6 ? 'border-primary-500 ring-2 ring-primary-500/10' : 'border-slate-200'">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs font-bold text-slate-800">طرح ۶ ماهه</span>
                                        <input type="radio" name="product_plan" :checked="productMonths === 6" class="accent-primary-600">
                                    </div>
                                    <div class="text-primary-600 font-black text-sm" x-text="formatPrice(Math.round((selectedProduct.price * 1.05) / 6)) + ' / قسط' "></div>
                                    <span class="text-[10px] text-amber-600 font-semibold bg-amber-50 px-2 py-0.5 rounded-md self-start">کارمزد بسیار اندک</span>
                                </label>
                            </div>

                            <!-- Installment Roadmap / Visual representation of payments -->
                            <div class="border-t border-slate-200/60 pt-4 space-y-3">
                                <span class="text-xs font-bold text-slate-500 block">برنامه پرداخت‌های شما در سیستم BNPL:</span>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                    <div class="bg-white p-3 rounded-xl border border-slate-100">
                                        <div class="text-[9px] font-bold text-slate-400">قسط اول (همین امروز)</div>
                                        <div class="text-xs font-extrabold text-slate-900 mt-1" x-text="productMonths === 6 ? formatPrice(Math.round((selectedProduct.price * 1.05) / 6)) + ' تومان' : formatPrice(Math.round(selectedProduct.price / productMonths)) + ' تومان'"></div>
                                    </div>
                                    <div class="bg-white p-3 rounded-xl border border-slate-100">
                                        <div class="text-[9px] font-bold text-slate-400">قسط دوم (۳۰ روز بعد)</div>
                                        <div class="text-xs font-extrabold text-slate-900 mt-1" x-text="productMonths === 6 ? formatPrice(Math.round((selectedProduct.price * 1.05) / 6)) + ' تومان' : formatPrice(Math.round(selectedProduct.price / productMonths)) + ' تومان'"></div>
                                    </div>
                                    <div class="bg-white p-3 rounded-xl border border-slate-100" :class="productMonths < 4 ? 'opacity-30 line-through bg-slate-100' : ''">
                                        <div class="text-[9px] font-bold text-slate-400">قسط سوم (۶۰ روز بعد)</div>
                                        <div class="text-xs font-extrabold text-slate-900 mt-1" x-text="productMonths >= 4 ? (productMonths === 6 ? formatPrice(Math.round((selectedProduct.price * 1.05) / 6)) + ' تومان' : formatPrice(Math.round(selectedProduct.price / productMonths)) + ' تومان') : '-'"></div>
                                    </div>
                                    <div class="bg-white p-3 rounded-xl border border-slate-100" :class="productMonths < 4 ? 'opacity-30 line-through bg-slate-100' : ''">
                                        <div class="text-[9px] font-bold text-slate-400">قسط چهارم (۹۰ روز بعد)</div>
                                        <div class="text-xs font-extrabold text-slate-900 mt-1" x-text="productMonths >= 4 ? (productMonths === 6 ? formatPrice(Math.round((selectedProduct.price * 1.05) / 6)) + ' تومان' : formatPrice(Math.round(selectedProduct.price / productMonths)) + ' تومان') : '-'"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Buy Button / Cart Action -->
                            <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-slate-200/60">
                                <button @click="addToCart(selectedProduct, productMonths)" class="flex-grow flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white py-4 rounded-xl font-bold transition-all hover:scale-[1.01] shadow-lg shadow-primary-500/10">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <span>خرید اقساطی با قسطی‌پی</span>
                                </button>
                                <button @click="setPage('home')" class="py-4 px-6 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-sm transition-all">
                                    بازگشت به خانه
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PAGE 3: ABOUT US -->
        <div x-show="page === 'about'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
                <!-- Banner Title -->
                <div class="text-center space-y-4">
                    <span class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl inline-block"><i class="fa-solid fa-rocket text-3xl"></i></span>
                    <h1 class="text-3xl sm:text-4xl font-black text-slate-900">درباره قسطی‌پی</h1>
                    <p class="text-slate-500 text-base max-w-xl mx-auto">ما خرید اقساطی را بدون واسطه و بدون دغدغه‌های سنتی به گوشی هوشمند شما آورده‌ایم.</p>
                </div>

                <!-- Mission Card -->
                <div class="bg-white border border-slate-100 p-8 rounded-3xl shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="w-1.5 h-6 bg-primary-600 rounded-full"></span> ماموریت ما چیست؟
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        دسترسی عادلانه به کردیت (اعتبار خرید) یکی از بزرگترین ابزارهای جوامع مدرن برای افزایش کیفیت زندگی افراد است. ما در قسطی‌پی بر این باوریم که فرآیند خرید نباید تحت تاثیر نوسانات نقدینگی ماهانه قرار بگیرد. پلتفرم با تلفیق تکنولوژی‌های نوین مالی (FinTech) و الگوریتم‌های هوش مصنوعی سنجش ریسک اعتباری، بستری ایمن، سریع و با کارمزد صفر را مهیا ساخته است.
                    </p>
                </div>

                <!-- Features Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white border border-slate-100 p-6 rounded-2xl flex gap-4">
                        <span class="p-3 bg-rose-50 text-rose-500 rounded-xl h-fit"><i class="fa-solid fa-percent text-xl"></i></span>
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-900 text-base">بدون بهره و سودهای نجومی</h4>
                            <p class="text-slate-500 text-xs leading-relaxed">در خریدهای ۲ و ۴ ماهه، دقیقاً به اندازه برچسب قیمت نقدی کالا پرداخت خواهید کرد؛ ریالی بیشتر دریافت نخواهد شد.</p>
                        </div>
                    </div>
                    <div class="bg-white border border-slate-100 p-6 rounded-2xl flex gap-4">
                        <span class="p-3 bg-emerald-50 text-emerald-500 rounded-xl h-fit"><i class="fa-solid fa-clock-rotate-left text-xl"></i></span>
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-900 text-base">بدون پرونده و بروکراسی اداری</h4>
                            <p class="text-slate-500 text-xs leading-relaxed">کل فرآیند به صورت دیجیتال در تلفن همراه شما ثبت و پردازش می‌شود و خبری از رفت‌و‌آمدهای مکرر نیست.</p>
                        </div>
                    </div>
                </div>

                <!-- Partner Logos Sim -->
                <div class="bg-slate-50 p-6 rounded-3xl text-center space-y-4">
                    <span class="text-xs font-bold text-slate-400">شرکا و بانک‌های همکار جهت تامین اعتبار مالی طرح‌ها</span>
                    <div class="flex flex-wrap items-center justify-center gap-8 opacity-60">
                        <div class="flex items-center gap-1 text-slate-500 font-extrabold text-sm border px-3 py-1.5 rounded-xl bg-white"><i class="fa-solid fa-building-columns text-slate-400"></i> بانک ملی ایران</div>
                        <div class="flex items-center gap-1 text-slate-500 font-extrabold text-sm border px-3 py-1.5 rounded-xl bg-white"><i class="fa-solid fa-building-columns text-slate-400"></i> بانک سامان</div>
                        <div class="flex items-center gap-1 text-slate-500 font-extrabold text-sm border px-3 py-1.5 rounded-xl bg-white"><i class="fa-solid fa-building-columns text-slate-400"></i> بانک پارسیان</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PAGE 4: CONTACT US -->
        <div x-show="page === 'contact'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Title -->
                <div class="text-center space-y-4 mb-16">
                    <span class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl inline-block"><i class="fa-solid fa-headset text-3xl"></i></span>
                    <h1 class="text-3xl sm:text-4xl font-black text-slate-900">تماس با پشتیبانی قسطی‌پی</h1>
                    <p class="text-slate-500 text-base max-w-xl mx-auto">سوالی دارید؟ یا به مشاوره خرید اعتباری نیاز دارید؟ تیم پشتیبانی ما ۲۴ ساعته در خدمت شماست.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                    <!-- Right column: Contact Info Details -->
                    <div class="lg:col-span-5 space-y-8">
                        <div class="bg-white border border-slate-100 p-8 rounded-3xl shadow-sm space-y-6">
                            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-3">
                                <span class="w-1.5 h-6 bg-primary-600 rounded-full"></span> اطلاعات تماس پلتفرم
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <span class="p-3 bg-primary-50 text-primary-600 rounded-xl"><i class="fa-solid fa-phone text-lg"></i></span>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-sm">تلفن پشتیبانی و مشاوره</h4>
                                        <p class="text-slate-500 text-xs mt-1" dir="ltr">۰۲۱-۹۱۰۰XXXX (خط ویژه)</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">پاسخگویی شبانه‌روزی حتی در روزهای تعطیل</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <span class="p-3 bg-primary-50 text-primary-600 rounded-xl"><i class="fa-solid fa-envelope text-lg"></i></span>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-sm">پست الکترونیکی (ایمیل)</h4>
                                        <p class="text-slate-500 text-xs mt-1">support@qestipay.ir</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">پاسخگویی به درخواست‌های رسمی حداکثر طی ۲۴ ساعت</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <span class="p-3 bg-primary-50 text-primary-600 rounded-xl"><i class="fa-solid fa-location-dot text-lg"></i></span>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-sm">دفتر مرکزی</h4>
                                        <p class="text-slate-500 text-xs mt-1 leading-relaxed">تهران، میدان ونک، پارک فناوری‌های مالی، طبقه ۴، واحد ۴۰۲</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ CTA -->
                        <div class="bg-gradient-to-tr from-slate-900 to-primary-950 p-8 rounded-3xl text-white relative overflow-hidden shadow-lg">
                            <div class="absolute top-0 left-0 translate-x-4 -translate-y-4 w-28 h-28 bg-white/5 rounded-full"></div>
                            <h4 class="font-extrabold text-base mb-2">به دنبال پاسخ سریع هستید؟</h4>
                            <p class="text-xs text-slate-300 leading-relaxed mb-6">قبل از تماس با پشتیبانی، بخش سوالات متداول را مطالعه کنید. شاید پاسخ سوال شما از قبل ثبت شده باشد.</p>
                            <a href="#" @click.prevent="showToast('بخش سوالات متداول در نسخه نهایی لاراول فعال خواهد شد.', 'info')" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border border-white/10">
                                <span>سوالات متداول اقساطی</span>
                                <i class="fa-solid fa-chevron-left text-[9px]"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Left column: Interactive Contact Form -->
                    <div class="lg:col-span-7">
                        <div class="bg-white border border-slate-100 p-8 rounded-3xl shadow-sm space-y-6">
                            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-100">
                                <span class="w-1.5 h-6 bg-primary-600 rounded-full"></span> ارسال پیام مستقیم
                            </h3>

                            <form @submit.prevent="submitContactForm()" class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Name -->
                                    <div class="space-y-1">
                                        <label class="block text-xs font-bold text-slate-600">نام و نام خانوادگی <span class="text-rose-500">*</span></label>
                                        <input type="text" x-model="contactName" required placeholder="مثال: علی رضایی" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-primary-500 focus:bg-white transition-all font-semibold">
                                    </div>
                                    <!-- Phone -->
                                    <div class="space-y-1">
                                        <label class="block text-xs font-bold text-slate-600">شماره تماس <span class="text-rose-500">*</span></label>
                                        <input type="tel" x-model="contactPhone" required placeholder="۰۹۱۲۳۴۵۶۷۸۹" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-primary-500 focus:bg-white transition-all text-left font-bold" dir="ltr">
                                    </div>
                                </div>

                                <!-- Subject -->
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-600">موضوع پیام</label>
                                    <input type="text" x-model="contactSubject" placeholder="مثال: سوال در مورد نحوه اعتبارسنجی" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-primary-500 focus:bg-white transition-all font-semibold">
                                </div>

                                <!-- Message -->
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-600">متن پیام شما <span class="text-rose-500">*</span></label>
                                    <textarea x-model="contactMessage" required rows="5" placeholder="پیام یا پرسش خود را اینجا بنویسید..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-primary-500 focus:bg-white transition-all font-semibold resize-none"></textarea>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="w-full py-3.5 bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm rounded-xl transition-all shadow-lg shadow-primary-500/10 flex items-center justify-center gap-2">
                                    <template x-if="!contactSubmitting">
                                        <span class="flex items-center gap-2">
                                            <i class="fa-solid fa-paper-plane"></i>
                                            <span>ارسال پیام پشتیبانی</span>
                                        </span>
                                    </template>
                                    <template x-if="contactSubmitting">
                                        <span class="flex items-center gap-2 animate-pulse">
                                            <i class="fa-solid fa-spinner animate-spin"></i>
                                            <span>در حال ارسال پیام...</span>
                                        </span>
                                    </template>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PAGE 5: REGISTRATION & CREDIT CHECK SIMULATION -->
        <div x-show="page === 'register'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="py-16">
            <div class="max-w-md mx-auto px-4">
                
                <!-- Registration Steps visual -->
                <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-md space-y-6">
                    <div class="text-center space-y-2 mb-6">
                        <h2 class="text-2xl font-black text-slate-900">سامانه احراز هویت قسطی‌پی</h2>
                        <p class="text-slate-400 text-xs">ثبت‌نام آنلاین و سنجش رتبه اعتباری برای دریافت اعتبار</p>
                    </div>

                    <!-- Step Indicators -->
                    <div class="flex items-center justify-between relative mb-8">
                        <div class="absolute h-0.5 bg-slate-100 left-0 right-0 top-1/2 -translate-y-1/2 z-0"></div>
                        
                        <div class="relative z-10 flex flex-col items-center gap-1">
                            <span class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs" :class="registerStep === 1 ? 'bg-primary-600 text-white' : 'bg-emerald-500 text-white'">۱</span>
                            <span class="text-[10px] font-bold text-slate-500">مشخصات اولیه</span>
                        </div>
                        <div class="relative z-10 flex flex-col items-center gap-1">
                            <span class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all" :class="registerStep === 2 ? 'bg-primary-600 text-white' : (registerStep > 2 ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500')">۲</span>
                            <span class="text-[10px] font-bold text-slate-500">بارگذاری کارت ملی</span>
                        </div>
                        <div class="relative z-10 flex flex-col items-center gap-1">
                            <span class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all" :class="registerStep === 3 ? 'bg-primary-600 text-white' : 'bg-slate-200 text-slate-500'">۳</span>
                            <span class="text-[10px] font-bold text-slate-500">دریافت اعتبار</span>
                        </div>
                    </div>

                    <!-- STEP 1 FORM: Basic information -->
                    <div x-show="registerStep === 1" class="space-y-4">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-600">شماره موبایل (باید به نام خودتان باشد):</label>
                            <div class="relative">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-mobile-screen-button"></i></span>
                                <input type="tel" x-model="regPhone" placeholder="۰۹۱۲۳۴۵۶۷۸۹" class="w-full pl-3 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-primary-500 focus:bg-white transition-all text-left font-bold" dir="ltr">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-600">کد ملی ۱۰ رقمی:</label>
                            <div class="relative">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-address-card"></i></span>
                                <input type="text" x-model="regNationalId" placeholder="۰۰۱۲۳۴۵۶۷۸" class="w-full pl-3 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-primary-500 focus:bg-white transition-all text-left font-bold" dir="ltr">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-600">نام و نام خانوادگی:</label>
                            <div class="relative">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-user"></i></span>
                                <input type="text" x-model="regName" placeholder="مثال: رضا محمدی" class="w-full pl-3 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-primary-500 focus:bg-white transition-all font-bold">
                            </div>
                        </div>

                        <button @click="submitStepOne()" class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-primary-500/10">
                            مرحله بعد: بارگذاری مدارک <i class="fa-solid fa-arrow-left ml-1"></i>
                        </button>
                    </div>

                    <!-- STEP 2 FORM: Document Upload simulation -->
                    <div x-show="registerStep === 2" class="space-y-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-600">تصویر واضح کارت ملی هوشمند:</label>
                            
                            <!-- Fake Drag n Drop -->
                            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center bg-slate-50 hover:bg-slate-100/50 transition-all cursor-pointer relative overflow-hidden flex flex-col items-center justify-center">
                                <template x-if="!nationalCardUploaded">
                                    <div class="space-y-2">
                                        <span class="text-3xl text-slate-300 block"><i class="fa-solid fa-id-card"></i></span>
                                        <span class="text-xs font-bold text-slate-700 block">فایل یا تصویر کارت ملی را انتخاب کنید</span>
                                        <span class="text-[10px] text-slate-400">فرمت‌های قابل قبول: JPG, PNG حداکثر ۵ مگابایت</span>
                                        <button @click="nationalCardUploaded = true" class="mt-3 bg-white border px-3 py-1.5 rounded-lg text-[11px] font-bold text-primary-600 hover:bg-slate-100">آپلود سریع فایل تستی</button>
                                    </div>
                                </template>
                                <template x-if="nationalCardUploaded">
                                    <div class="space-y-2">
                                        <span class="text-3xl text-emerald-500 block"><i class="fa-solid fa-circle-check"></i></span>
                                        <span class="text-xs font-extrabold text-emerald-700 block">تصویر با موفقیت بارگذاری شد</span>
                                        <span class="text-[10px] text-slate-400 block">national_id_card.jpg (1.2 MB)</span>
                                        <button @click="nationalCardUploaded = false" class="text-rose-500 font-bold text-[10px]">حذف و بارگذاری مجدد</button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-xl flex gap-3 text-indigo-800 text-xs leading-relaxed">
                            <i class="fa-solid fa-circle-exclamation mt-0.5 text-base"></i>
                            <p>احراز هویت به صورت کاملاً خودکار بر اساس انطباق شماره ملی، شماره موبایل و استعلام ثبت احوال در مرحله بعد انجام خواهد شد.</p>
                        </div>

                        <div class="flex gap-4">
                            <button @click="registerStep = 1" class="w-1/3 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition-all">
                                قبلی
                            </button>
                            <button @click="submitStepTwo()" class="w-2/3 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs rounded-xl transition-all shadow-md shadow-primary-500/10">
                                ارسال و استعلام اعتبار بانکی <i class="fa-solid fa-arrow-left ml-1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3 FORM: Processing & result simulation -->
                    <div x-show="registerStep === 3" class="space-y-6 text-center">
                        <!-- Loading State -->
                        <div x-show="creditChecking" class="py-12 space-y-4">
                            <div class="inline-block relative w-12 h-12">
                                <div class="absolute border-4 border-slate-100 rounded-full w-full h-full"></div>
                                <div class="absolute border-4 border-primary-600 border-t-transparent rounded-full w-full h-full animate-spin"></div>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm">در حال اتصال به سامانه‌های اعتبارسنجی مرکزی...</h4>
                            <p class="text-slate-400 text-[10px]">این فرآیند به طور معمول ۵ الی ۱۰ ثانیه زمان می‌برد.</p>
                        </div>

                        <!-- Success Result -->
                        <div x-show="!creditChecking && userApproved" class="space-y-6">
                            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-3xl mx-auto">
                                <i class="fa-solid fa-face-smile"></i>
                            </div>

                            <div class="space-y-2">
                                <h4 class="font-extrabold text-slate-900 text-lg">تبریک! اعتبار شما تایید شد</h4>
                                <p class="text-slate-500 text-xs">رتبه اعتباری شما ممتاز ارزیابی شده و سقف اعتبار اولیه زیر فعال گردید:</p>
                            </div>

                            <!-- Big Wallet Card representing Credit -->
                            <div class="bg-gradient-to-tr from-primary-900 to-indigo-800 p-6 rounded-2xl text-white text-right relative overflow-hidden shadow-lg shadow-primary-950/20">
                                <div class="absolute top-0 left-0 translate-x-4 -translate-y-4 w-28 h-28 bg-white/5 rounded-full"></div>
                                <div class="flex justify-between items-center mb-6">
                                    <span class="text-[10px] font-bold text-primary-200 tracking-wider">سقف کارت اعتباری قسطی‌پی</span>
                                    <i class="fa-solid fa-bolt text-lg text-yellow-400"></i>
                                </div>
                                <div class="text-[11px] text-primary-100">اعتبار اختصاص یافته:</div>
                                <div class="text-2xl font-black mt-1" x-text="formatPrice(creditLimit) + ' تومان'"></div>
                                <div class="flex justify-between items-end mt-8">
                                    <div class="text-[10px] text-primary-200">کد کاربر: <span x-text="regNationalId"></span></div>
                                    <span class="text-[10px] bg-white/15 px-2.5 py-1 rounded-lg border border-white/10 font-bold text-white">کیف پول فعال</span>
                                </div>
                            </div>

                            <p class="text-[11px] text-slate-400">اکنون می‌توانید به راحتی به بخش فروشگاه برگشته و هر کالایی را تا سقف مجاز به صورت قسطی خرید کنید.</p>

                            <button @click="completeRegistration()" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all">
                                شروع خرید با اعتبار قسطی‌پی <i class="fa-solid fa-cart-shopping ml-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- SHOPPING CART SLIDE-OUT PANEL (MOCK INTERACTIVE CART) -->
    <div x-show="cartOpen" class="fixed inset-0 z-50 overflow-hidden" x-transition>
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="toggleCart()"></div>

        <div class="absolute inset-y-0 left-0 max-w-md w-full bg-white shadow-2xl flex flex-col justify-between" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
            <div class="p-6 overflow-y-auto space-y-6 flex-grow">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-shopping-cart text-primary-600"></i>
                        <span>سبد خرید اقساطی شما</span>
                    </h3>
                    <button @click="toggleCart()" class="p-2 hover:bg-slate-100 rounded-lg text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <!-- Empty Cart State -->
                <template x-if="cart.length === 0">
                    <div class="text-center py-12 space-y-4">
                        <span class="text-4xl text-slate-300 block"><i class="fa-solid fa-basket-shopping"></i></span>
                        <p class="text-slate-500 text-xs font-bold">سبد خرید شما فعلا خالی است!</p>
                        <button @click="setPage('home'); toggleCart()" class="text-primary-600 font-bold text-xs underline">شروع افزودن محصولات به سبد</button>
                    </div>
                </template>

                <!-- Cart Items -->
                <template x-if="cart.length > 0">
                    <div class="space-y-4">
                        <template x-for="(cartItem, index) in cart" :key="index">
                            <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100 relative">
                                <!-- Miniature Product Thumbnail in Cart -->
                                <div class="bg-white border rounded-xl overflow-hidden w-16 h-16 flex-shrink-0">
                                    <img :src="cartItem.product.image" onerror="this.onerror=null; this.src='https://placehold.co/150x150/eceff1/455a64'" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-grow space-y-1">
                                    <h4 class="font-bold text-slate-900 text-xs line-clamp-1 pl-4" x-text="cartItem.product.name"></h4>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] text-slate-400 font-bold" x-text="cartItem.months + ' قسط'"></span>
                                        <span class="text-xs font-extrabold text-slate-950" x-text="formatPrice(cartItem.product.price) + ' تومان'"></span>
                                    </div>
                                    <div class="text-[10px] text-primary-700 font-black" x-text="'مبلغ هر قسط: ' + formatPrice(Math.round(cartItem.product.price / cartItem.months)) + ' تومان'"></div>
                                </div>
                                <button @click="removeFromCart(index)" class="absolute top-2 left-2 text-rose-500 hover:text-rose-600 p-1 rounded"><i class="fa-solid fa-trash-can text-xs"></i></button>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <!-- Checkout Action and calculation summary -->
            <template x-if="cart.length > 0">
                <div class="p-6 border-t border-slate-100 bg-slate-50 space-y-4">
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs text-slate-500">
                            <span>جمع کل خرید نقدی:</span>
                            <span class="text-slate-900 font-bold" x-text="formatPrice(getCartTotal()) + ' تومان'"></span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-500">
                            <span>پیش‌پرداخت امروز:</span>
                            <span class="text-slate-900 font-extrabold text-emerald-600" x-text="formatPrice(Math.round(getCartDownpayment())) + ' تومان'"></span>
                        </div>
                        <div class="flex justify-between text-xs font-bold text-slate-900 border-t border-slate-200/50 pt-2">
                            <span>مجموع باقیمانده اقساط:</span>
                            <span x-text="formatPrice(Math.round(getCartTotal() - getCartDownpayment())) + ' تومان'"></span>
                        </div>
                    </div>

                    <!-- Complete Order Simulator Button -->
                    <button @click="processCheckout()" class="w-full py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-sm shadow-md shadow-primary-500/10 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-shield-check"></i>
                        <span>ثبت نهایی سفارش اعتباری (BNPL)</span>
                    </button>
                </div>
            </template>
        </div>
    </div>

    <!-- NOTIFICATION SYSTEM (TOASTS SIMULATION) -->
    <div class="fixed bottom-4 right-4 z-[60] space-y-2">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition class="bg-slate-900 text-white text-xs font-bold px-5 py-3.5 rounded-xl shadow-lg border border-slate-800 flex items-center gap-2.5">
                <i :class="toast.type === 'success' ? 'fa-solid fa-circle-check text-emerald-500' : 'fa-solid fa-triangle-exclamation text-rose-500'"></i>
                <span x-text="toast.message"></span>
            </div>
        </template>
    </div>

    <!-- FOOTER -->
    <footer class="bg-slate-950 text-slate-400 py-12 border-t border-slate-900 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8 border-b border-slate-900 pb-8">
                <!-- Info -->
                <div class="space-y-4">
                    <a href="#" class="flex items-center gap-2">
                        <span class="p-2 bg-primary-600 text-white rounded-xl">
                            <i class="fa-solid fa-bolt text-lg"></i>
                        </span>
                        <span class="font-black text-lg text-white">قسطی‌پی</span>
                    </a>
                    <p class="text-xs leading-relaxed text-slate-500">فناوری نوین پرداخت اعتباری در لحظه، برای تمام هموطنان ایران‌زمین به صورت عادلانه و هوشمند.</p>
                </div>

                <!-- Fast Links -->
                <div>
                    <h5 class="text-white text-xs font-black mb-4">بخش‌های وب‌سایت</h5>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#" @click.prevent="setPage('home')" class="hover:text-white transition-all">صفحه اصلی فروشگاه</a></li>
                        <li><a href="#" @click.prevent="setPage('product')" class="hover:text-white transition-all">لیست محصولات دیجیتال</a></li>
                        <li><a href="#" @click.prevent="setPage('about')" class="hover:text-white transition-all">درباره پلتفرم ما</a></li>
                        <li><a href="#" @click.prevent="setPage('contact')" class="hover:text-white transition-all">ارتباط با ما</a></li>
                    </ul>
                </div>

                <!-- Features -->
                <div>
                    <h5 class="text-white text-xs font-black mb-4">قوانین و پشتیبانی</h5>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#" class="hover:text-white transition-all">شرایط خوش‌حسابی و اعتبار</a></li>
                        <li><a href="#" class="hover:text-white transition-all">سوالات متداول اقساطی</a></li>
                        <li><a href="#" class="hover:text-white transition-all">تماس با پشتیبانی ۲۴ ساعته</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h5 class="text-white text-xs font-black mb-4">ارتباط با ما</h5>
                    <p class="text-xs leading-relaxed text-slate-500">تهران، میدان ونک، پارک فناوری‌های مالی، طبقه ۴، واحد ۴۰۲</p>
                    <div class="flex gap-4 mt-4 text-slate-500">
                        <a href="#" class="hover:text-white"><i class="fa-brands fa-instagram text-base"></i></a>
                        <a href="#" class="hover:text-white"><i class="fa-brands fa-linkedin text-base"></i></a>
                        <a href="#" class="hover:text-white"><i class="fa-brands fa-telegram text-base"></i></a>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center text-[10px] text-slate-600 gap-4">
                <p>© ۱۴۰۵ کلیه حقوق برای قسطی‌پی محفوظ است. قدرت گرفته از معماری لاراول و وب‌سرویس‌های هوشمند اعتباری.</p>
                <div class="flex gap-4">
                    <span class="hover:text-slate-400">سیاست حریم خصوصی</span>
                    <span class="hover:text-slate-400">مقررات اعتبارسنجی</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>