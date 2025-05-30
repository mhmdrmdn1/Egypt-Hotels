(function() {
    const fab = document.createElement('button');
    fab.className = 'chatbot-fab';
    fab.title = 'Chat with us';
    fab.setAttribute('aria-label', 'Open chat');
    fab.innerHTML = '<svg aria-hidden="true" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>';
    document.body.appendChild(fab);

    const chatWindow = document.createElement('div');
    chatWindow.className = 'chatbot-window';
    chatWindow.setAttribute('role', 'dialog');
    chatWindow.setAttribute('aria-modal', 'true');
    chatWindow.setAttribute('aria-labelledby', 'chatbot-title');
    chatWindow.innerHTML = `
        <div class="chatbot-header">
            <div>
                <button class="new-chat-btn" aria-label="Start new chat" title="New Chat">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </span>
                </button>
                <button class="chatbot-lang" data-lang="en" aria-label="English language">EN</button>
                <button class="chatbot-lang" data-lang="ar" aria-label="Arabic language">عربي</button>
                <button class="chatbot-close" title="Close" aria-label="Close chat">&times;</button>
            </div>
        </div>
        <div class="chatbot-messages" aria-live="polite" aria-relevant="additions"></div>
        <form class="chatbot-input-area" aria-label="Send message form">
            <input class="chatbot-input" type="text" placeholder="Type your message..." autocomplete="off" required aria-label="Chat input"/>
            <button class="chatbot-send" type="submit" aria-label="Send message">&#9658;</button>
        </form>
    `;
    document.body.appendChild(chatWindow);

    // Get DOM elements
    const closeBtn = chatWindow.querySelector('.chatbot-close');
    const messagesArea = chatWindow.querySelector('.chatbot-messages');
    const newChatBtn = chatWindow.querySelector('.new-chat-btn');
    const inputForm = chatWindow.querySelector('.chatbot-input-area');
    const input = chatWindow.querySelector('.chatbot-input');
    const langBtns = chatWindow.querySelectorAll('.chatbot-lang');
    const sendBtn = chatWindow.querySelector('.chatbot-send');

    let lang = 'en'; // Default language is English
    let isOpen = false;
    let hotelsList = [];

    // Fetch hotels from API on load
    fetch('/Booking-Hotel-Project/pages/api/hotels_api.php')
        .then(res => res.json())
        .then(data => {
            if (Array.isArray(data)) {
                hotelsList = data;
                console.log('Hotels loaded:', hotelsList.map(h => h.name));
            }
        })
        .catch(err => console.error('Failed to load hotels:', err));

    // Language handling
    langBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            setLanguage(btn.getAttribute('data-lang'));
            if (messagesArea.childElementCount === 0) {
                addWelcomeMessage();
            }
            input.focus();
        });
    });

    function setLanguage(newLang) {
        lang = newLang;
        console.log('Language set to:', lang);
        document.querySelectorAll('.chatbot-lang').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.lang === newLang) {
                btn.classList.add('active');
            }
        });
        updateUIForLanguage();
    }

    function updateUIForLanguage() {
        input.placeholder = lang === 'ar' ? 'اكتب رسالتك...' : 'Type your message...';
        if (lang === 'ar') {
            chatWindow.style.direction = 'rtl';
            chatWindow.style.textAlign = 'right';
        } else {
            chatWindow.style.direction = 'ltr';
            chatWindow.style.textAlign = 'left';
        }
    }

    function addWelcomeMessage() {
        const welcomeMsg = lang === 'ar' ? 
            'مرحباً بك في Egypt Hotels! كيف يمكنني مساعدتك اليوم؟' : 
            'Welcome to Egypt Hotels! How can I assist you today?';
        addBotMessage(welcomeMsg);
    }

    function clearChat() {
        messagesArea.innerHTML = '';
        addWelcomeMessage();
        input.disabled = false;
        sendBtn.disabled = false;
        input.focus();
    }

    // Event listeners for UI controls
    fab.addEventListener('click', () => {
        isOpen = !isOpen;
        chatWindow.classList.toggle('active', isOpen);
        if (isOpen) {
            input.focus();
            if (messagesArea.childElementCount === 0) {
                addWelcomeMessage();
            }
        }
    });

    closeBtn.addEventListener('click', () => {
        isOpen = false;
        chatWindow.classList.remove('active');
        fab.focus();
    });

    newChatBtn.addEventListener('click', (e) => {
        e.preventDefault();
        clearChat();
    });

    // Message handling functions
    function addMessage(text, sender) {
        const msg = document.createElement('div');
        msg.className = 'chatbot-msg ' + sender;
        msg.dir = lang === 'ar' ? 'rtl' : 'ltr';

        const messageText = document.createElement('span');
        messageText.textContent = text;

        const timestamp = document.createElement('span');
        timestamp.className = 'timestamp';
        const now = new Date();
        const h = now.getHours().toString().padStart(2, '0');
        const m = now.getMinutes().toString().padStart(2, '0');
        timestamp.textContent = `${h}:${m}`;

        msg.appendChild(messageText);
        msg.appendChild(timestamp);

        messagesArea.appendChild(msg);
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }

    function addBotMessage(text) {
        addMessage(text, 'bot');
    }

    function addUserMessage(text) {
        addMessage(text, 'user');
    }

    function showTyping() {
        const typing = document.createElement('div');
        typing.className = 'chatbot-typing';
        typing.innerHTML = `
            <span></span><span></span><span></span> ${lang === 'ar' ? 'يكتب...' : 'typing...'}
        `;
        typing.dir = lang === 'ar' ? 'rtl' : 'ltr';
        messagesArea.appendChild(typing);
        messagesArea.scrollTop = messagesArea.scrollHeight;
        return typing;
    }

    // Message processing
    inputForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const userMsg = input.value.trim();
        if (!userMsg) return;
        addUserMessage(userMsg);
        input.value = '';
        const typing = showTyping();
        await new Promise(resolve => setTimeout(resolve, 500));
        const botResponse = getBotResponse(userMsg, lang);
        typing.remove();
        addBotMessage(botResponse);
    });

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            chatWindow.classList.remove('active');
            isOpen = false;
            fab.focus();
        }
    });

    // Bot response logic
    function getBotResponse(msg, currentLang) {
        msg = msg.toLowerCase();
        console.log('Processing message:', msg, 'in language:', currentLang, 'Current lang variable:', lang);

        const patterns = {
            ar: {
                greeting: /(مرحبا|اهلا|السلام|اهلاً|هلا|صباح الخير|مساء الخير)/,
                thanks: /(شكرا|شكراً|مشكور|مشكورة|تسلم|تسلمي)/,
                availableHotels: /(الفنادق المتوفرة|الفنادق الموجودة|الفنادق المتاحة|الفنادق في الموقع|اريد معرفه الفنادق|معرفة الفنادق|الفنادق)/,
                cityHotels: /(فنادق في ([\u0600-\u06FF\w\s]+)|ما هي الفنادق المتوفرة في ([\u0600-\u06FF\w\s]+)|اريد فنادق في ([\u0600-\u06FF\w\s]+))/,
                hotelDetails: /(تفاصيل فندق (.+)|معلومات عن فندق (.+)|اريد تفاصيل عن فندق (.+))/,
                booking: /(كيف احجز|طريقة الحجز|اريد الحجز|كيف يمكنني الحجز|اريد حجز فندق|حجز فندق)/,
                price: /(كم سعر|ما السعر|الاسعار|سعر الغرفة|سعر الفندق|كم تكلفة)/,
                payment: /(طرق الدفع|كيف ادفع|الدفع|بطاقة|كاش|فيزا|ماستر)/,
                cancel: /(الغاء الحجز|سياسة الالغاء|هل يمكنني الغاء|كيف الغي|الغاء)/,
                kids: /(الاطفال|سياسة الاطفال|هل الاطفال مسموح|هل يوجد خدمات اطفال)/,
                pets: /(الحيوانات|الحيوانات الأليفة|هل الحيوانات مسموحة|هل يسمح بالحيوانات)/,
                amenities: /(مرافق|خدمات|وسائل الراحة|مسبح|جيم|مطعم|واي فاي|انترنت|موقف سيارات)/,
                location: /(اين يقع|موقع الفندق|العنوان|كيف اصل|خريطة|الموقع الجغرافي)/,
                contact: /(تواصل|اتصال|رقم|بريد|كيف اتواصل|خدمة العملاء)/,
                security: /(امان|حماية|هل الموقع آمن|هل الدفع آمن|خصوصية|سياسة الخصوصية)/,
                roomTypes: /(انواع الغرف|نوع الغرف|غرف متوفرة في فندق (.+)|غرف فندق (.+))/,
                offers: /(عروض|خصومات|هل يوجد عرض|كود خصم|تخفيضات)/,
                checkin: /(تسجيل الدخول|موعد الوصول|وقت الدخول|متى استطيع الدخول|متى يمكنني الوصول|check in|موعد تسجيل الدخول)/,
                checkout: /(تسجيل الخروج|موعد المغادرة|وقت الخروج|متى يجب المغادرة|متى يجب الخروج|check out|موعد تسجيل الخروج)/,
                reviews: /(تقييم الفندق|تقييمات|آراء النزلاء|مراجعات|اراء|تقييمات النزلاء)/,
                photos: /(صور الفندق|صور الغرف|معرض الصور|اريد صور|عرض الصور)/,
                connectRooms: /(غرف متصلة|هل يوجد غرف متصلة|اريد غرفتين متصلتين)/,
                smoking: /(التدخين|هل التدخين مسموح|سياسة التدخين)/
            },
            en: {
                greeting: /(hi|hello|hey|good morning|good afternoon|good evening)/,
                thanks: /(thanks|thank you|appreciate it)/,
                availableHotels: /(available hotels|hotels list|hotels in site|what hotels do you have|show me hotels|list hotels)/,
                cityHotels: /(hotels in ([a-zA-Z\s]+)|what hotels are available in ([a-zA-Z\s]+)|i want hotels in ([a-zA-Z\s]+))/,
                hotelDetails: /(hotel details for (.+)|tell me about hotel (.+)|info about hotel (.+))/,
                booking: /(how to book|booking process|i want to book|how can i book|book a hotel|make a reservation)/,
                price: /(price|how much|cost|room price|hotel price|how much is)/,
                payment: /(payment methods|how to pay|pay|credit card|cash|visa|mastercard)/,
                cancel: /(cancel booking|cancellation policy|can i cancel|how to cancel|cancel)/,
                kids: /(kids|children|child policy|are kids allowed|kids services)/,
                pets: /(pets|pet policy|are pets allowed|pet friendly|animals)/,
                amenities: /(amenities|facilities|services|pool|gym|restaurant|wifi|internet|parking)/,
                location: /(where is|hotel location|address|how to reach|map|geolocation)/,
                contact: /(contact|call|phone|email|how to contact|customer service)/,
                security: /(security|safe|is the site safe|is payment secure|privacy|privacy policy)/,
                roomTypes: /(room types|what rooms|available rooms at (.+)|rooms at (.+))/,
                offers: /(offers|discounts|any offer|discount code|promotion)/,
                checkin: /(check in|arrival time|when can i check in|when can i arrive|check-in time)/,
                checkout: /(check out|departure time|when should i leave|checkout time)/,
                reviews: /(hotel rating|reviews|guest reviews|opinions|ratings)/,
                photos: /(hotel photos|room photos|gallery|show photos|see images)/,
                connectRooms: /(connecting rooms|are there connecting rooms|i want two connected rooms)/,
                smoking: /(smoking|is smoking allowed|smoking policy)/
            }
        };

        const messages = {
            ar: {
                greeting: "مرحباً بك في Egypt Hotels! كيف يمكنني مساعدتك اليوم؟",
                thanks: "شكراً لك! هل هناك شيء آخر تريد معرفته؟",
                availableHotels: hotelsList.length > 0 ? "الفنادق المتوفرة لدينا:\n" + hotelsList.map(h => `- ${h.name}`).join("\n") : "لا توجد فنادق متوفرة حالياً.",
                cityHotels: (msg) => {
                    // يدعم عدة صيغ للسؤال عن فنادق مدينة
                    const match = msg.match(/فنادق في ([\u0600-\u06FF\w\s]+)/) ||
                                  msg.match(/ما هي الفنادق المتوفرة في ([\u0600-\u06FF\w\s]+)/) ||
                                  msg.match(/اريد فنادق في ([\u0600-\u06FF\w\s]+)/);
                    if (match && match[1]) {
                        const city = match[1].trim();
                        const hotels = hotelsList.filter(h => h.location && h.location.includes(city));
                        if (hotels.length > 0) {
                            return `الفنادق في ${city}:\n` + hotels.map(h => `- ${h.name}`).join("\n");
                        } else {
                            return `لا توجد فنادق متوفرة في ${city} حالياً.`;
                        }
                    }
                    return "يرجى تحديد اسم المدينة بشكل صحيح.";
                },
                hotelDetails: (msg) => {
                    const match = msg.match(/فندق (.+)/);
                    if (match && match[1]) {
                        const name = match[1].trim();
                        const hotel = hotelsList.find(h => h.name.includes(name));
                        if (hotel) {
                            return `تفاصيل فندق ${hotel.name}:\nالموقع: ${hotel.location}\nالسعر: ${hotel.price} جنيه\nالتقييم: ${hotel.rating}⭐\nالمزايا: ${hotel.features ? hotel.features.join(", ") : ''}`;
                        } else {
                            return `لم يتم العثور على فندق بهذا الاسم.`;
                        }
                    }
                    return "يرجى تحديد اسم الفندق بشكل صحيح.";
                },
                booking: "يمكنك الحجز بسهولة عبر الموقع باختيار المدينة وتواريخ الإقامة وعدد الضيوف، ثم الضغط على زر الحجز واتباع التعليمات.",
                price: "تختلف الأسعار حسب الفندق والغرفة والموسم. يمكنك معرفة السعر الدقيق عند اختيار الفندق والغرفة.",
                payment: "نقبل جميع طرق الدفع: بطاقات الائتمان (فيزا/ماستر كارد)، والدفع عند الوصول في بعض الفنادق.",
                cancel: "سياسة الإلغاء تختلف حسب الفندق. يمكنك مراجعة سياسة الإلغاء في صفحة الفندق قبل إتمام الحجز.",
                kids: "معظم الفنادق ترحب بالأطفال وتوفر خدمات خاصة لهم. يرجى مراجعة سياسة الفندق المحدد.",
                pets: "بعض الفنادق تسمح بالحيوانات الأليفة. تحقق من سياسة الفندق قبل الحجز.",
                amenities: "نوفر مرافق متنوعة مثل: مسبح، جيم، مطعم، واي فاي، موقف سيارات، وغيرها حسب الفندق.",
                location: "يمكنك معرفة موقع الفندق من صفحة تفاصيل الفندق أو عبر الخريطة في الموقع.",
                contact: "للتواصل معنا: هاتف 0123456789 أو بريد info@hotel-booking.com أو عبر صفحة التواصل في الموقع.",
                security: "موقعنا آمن وبياناتك محمية. جميع عمليات الدفع مشفرة ونلتزم بسياسة الخصوصية.",
                roomTypes: (msg) => {
                    const match = msg.match(/فندق (.+)/);
                    if (match && match[1]) {
                        const name = match[1].trim();
                        const hotel = hotelsList.find(h => h.name.includes(name));
                        if (hotel && hotel.features) {
                            return `أنواع الغرف في فندق ${hotel.name}:\n${hotel.features.join("، ")}`;
                        } else {
                            return `لم يتم العثور على بيانات الغرف لهذا الفندق.`;
                        }
                    }
                    return "يرجى تحديد اسم الفندق لمعرفة أنواع الغرف.";
                },
                offers: "نعم، لدينا عروض وخصومات موسمية. تابع صفحة العروض أو اشترك في النشرة البريدية للحصول على كود خصم.",
                checkin: "وقت تسجيل الدخول عادة من الساعة 2:00 ظهرًا. يرجى مراجعة سياسة الفندق المحدد.",
                checkout: "وقت تسجيل الخروج عادة حتى الساعة 12:00 ظهرًا. يرجى مراجعة سياسة الفندق المحدد.",
                reviews: (msg) => {
                    const match = msg.match(/فندق (.+)/);
                    if (match && match[1]) {
                        const name = match[1].trim();
                        const hotel = hotelsList.find(h => h.name.includes(name));
                        if (hotel && hotel.rating) {
                            return `تقييم فندق ${hotel.name}: ${hotel.rating} من 5 بناءً على آراء النزلاء.`;
                        } else {
                            return `لا توجد تقييمات متاحة لهذا الفندق.`;
                        }
                    }
                    return "يرجى تحديد اسم الفندق لمعرفة التقييمات.";
                },
                photos: (msg) => "يمكنك مشاهدة صور الفندق والغرف في صفحة تفاصيل الفندق على الموقع.",
                connectRooms: "بعض الفنادق توفر غرفًا متصلة. يرجى تحديد الفندق أو التواصل مع خدمة العملاء للتأكد.",
                smoking: "سياسة التدخين تختلف حسب الفندق. يرجى مراجعة صفحة الفندق أو التواصل معنا للمزيد من التفاصيل."
            },
            en: {
                greeting: "Welcome to Egypt Hotels! How can I help you today?",
                thanks: "Thank you! Is there anything else you'd like to know?",
                availableHotels: hotelsList.length > 0 ? "Available hotels:\n" + hotelsList.map(h => `- ${h.name}`).join("\n") : "No hotels are currently available.",
                cityHotels: (msg) => {
                    // Supports multiple question forms for hotels in a city
                    const match = msg.match(/hotels in ([a-zA-Z\s]+)/i) ||
                                  msg.match(/what hotels are available in ([a-zA-Z\s]+)/i) ||
                                  msg.match(/i want hotels in ([a-zA-Z\s]+)/i);
                    if (match && match[1]) {
                        const city = match[1].trim();
                        const hotels = hotelsList.filter(h => h.location && h.location.toLowerCase().includes(city.toLowerCase()));
                        if (hotels.length > 0) {
                            return `Hotels in ${city}:\n` + hotels.map(h => `- ${h.name}`).join("\n");
                        } else {
                            return `No hotels found in ${city}.`;
                        }
                    }
                    return "Please specify the city name correctly.";
                },
                hotelDetails: (msg) => {
                    const match = msg.match(/hotel (.+)/);
                    if (match && match[1]) {
                        const name = match[1].trim();
                        const hotel = hotelsList.find(h => h.name.toLowerCase().includes(name.toLowerCase()));
                        if (hotel) {
                            return `Hotel details for ${hotel.name}:\nLocation: ${hotel.location}\nPrice: ${hotel.price} EGP\nRating: ${hotel.rating}⭐\nFeatures: ${hotel.features ? hotel.features.join(", ") : ''}`;
                        } else {
                            return `No hotel found with that name.`;
                        }
                    }
                    return "Please specify the hotel name correctly.";
                },
                booking: "You can easily book through the website by selecting the city, stay dates, and number of guests, then clicking the book button and following the instructions.",
                price: "Prices vary depending on the hotel, room, and season. You can see the exact price when you select the hotel and room.",
                payment: "We accept all payment methods: credit cards (Visa/MasterCard), and pay at hotel for some hotels.",
                cancel: "Cancellation policy varies by hotel. Please check the hotel's cancellation policy before booking.",
                kids: "Most hotels welcome children and offer special services for them. Please check the specific hotel's policy.",
                pets: "Some hotels allow pets. Please check the hotel's policy before booking.",
                amenities: "We offer various amenities such as pool, gym, restaurant, WiFi, parking, and more depending on the hotel.",
                location: "You can find the hotel's location on the hotel details page or via the map on the website.",
                contact: "Contact us: Phone 0123456789, Email info@hotel-booking.com, or via the contact page on the website.",
                security: "Our site is secure and your data is protected. All payments are encrypted and we adhere to the privacy policy.",
                roomTypes: (msg) => {
                    const match = msg.match(/at (.+)/);
                    if (match && match[1]) {
                        const name = match[1].trim();
                        const hotel = hotelsList.find(h => h.name.toLowerCase().includes(name.toLowerCase()));
                        if (hotel && hotel.features) {
                            return `Room types at ${hotel.name}:\n${hotel.features.join(", ")}`;
                        } else {
                            return `No room data found for this hotel.`;
                        }
                    }
                    return "Please specify the hotel name to know the room types.";
                },
                offers: "Yes, we have seasonal offers and discounts. Check the offers page or subscribe to our newsletter for a discount code.",
                checkin: "Check-in time is usually from 2:00 PM. Please check the specific hotel's policy.",
                checkout: "Check-out time is usually until 12:00 PM. Please check the specific hotel's policy.",
                reviews: (msg) => {
                    const match = msg.match(/hotel (.+)/);
                    if (match && match[1]) {
                        const name = match[1].trim();
                        const hotel = hotelsList.find(h => h.name.toLowerCase().includes(name.toLowerCase()));
                        if (hotel && hotel.rating) {
                            return `Hotel rating for ${hotel.name}: ${hotel.rating} out of 5 based on guest reviews.`;
                        } else {
                            return `No reviews available for this hotel.`;
                        }
                    }
                    return "Please specify the hotel name to know the reviews.";
                },
                photos: (msg) => "You can view hotel and room photos on the hotel details page on the website.",
                connectRooms: "Some hotels offer connecting rooms. Please specify the hotel or contact customer service to confirm.",
                smoking: "Smoking policy varies by hotel. Please check the hotel page or contact us for more details."
            }
        };

        const currentPatterns = patterns[currentLang];
        const currentMessages = messages[currentLang];

        for (const [key, pattern] of Object.entries(currentPatterns)) {
            if (typeof currentMessages[key] === 'function' && pattern.test(msg)) {
                return currentMessages[key](msg);
            } else if (pattern.test(msg)) {
                return currentMessages[key];
            }
        }

        return currentLang === 'ar' 
            ? "عذراً، لم أتمكن من فهم سؤالك. اسألني عن خدمات الحجز، الأسعار، المرافق، أو التواصل."
            : "I'm sorry, I couldn't understand your question. Please ask about booking services, prices, facilities, or contact information.";
    }

    // Initialize chatbot
    function init() {
        // Set initial language
        const defaultLangBtn = chatWindow.querySelector('.chatbot-lang[data-lang="en"]');
        if (defaultLangBtn) {
            defaultLangBtn.classList.add('active');
        }
        
        // Add welcome message if chat is empty
        if (messagesArea.childElementCount === 0) {
            addWelcomeMessage();
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})(); 