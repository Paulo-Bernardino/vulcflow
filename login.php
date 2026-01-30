<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CureTire Flow B2</title>
    <link rel="shortcut icon" href="layout/img/favicon.png" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        'brand-blue': '#003366',
                        'brand-light': '#1e40af',
                        'brand-grey': '#4b5563',
                    },
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Suas animações do Modal */
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); opacity: 1; }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); }
        }
        @keyframes scaleCheck {
            0% { transform: scale(0); }
            100% { transform: scale(1); }
        }
        .animate-bounceIn { animation: bounceIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards; }
        .animate-scaleCheck { animation: scaleCheck 0.3s 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) both; }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col font-sans">
    
    <header class="bg-brand-blue border-b-2 border-[#FEDB00] shadow-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-3">
            <div class="flex items-center justify-between">
                <div class="hidden md:flex items-center gap-5 w-1/3">
                    <span class="text-[#FEDB00] italic font-black text-3xl tracking-tighter uppercase select-none">Goodyear</span>
                    <div class="h-7 w-[1px] bg-white/20"></div>
                </div>

                <div class="flex-1 text-center">
                    <h1 class="text-white font-bold text-base md:text-lg tracking-widest uppercase italic leading-none">
                        CureTire <span class="text-[#FEDB00]">Flow</span>
                    </h1>
                    <p class="text-[9px] text-blue-300 uppercase tracking-[0.2em] font-medium mt-1">Vulcanização B2</p>
                </div>
                <div class="w-1/3"></div>
            </div>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center p-6 bg-slate-100">
        <div class="w-full max-w-[400px]">
            <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden border border-gray-100">
                <div class="bg-brand-blue p-8 text-center border-b-4 border-[#FEDB00]">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4 rotate-3 border border-white/20">
                        <i class="fa-solid fa-shield-halved text-[#FEDB00] text-2xl"></i>
                    </div>
                    <h2 class="text-white font-black italic uppercase tracking-tighter text-2xl">Acesso Restrito</h2>
                </div>

                <form id="loginForm" class="p-8 space-y-5">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-brand-blue/60 ml-1">ID do Operador</label>
                        <input type="text" id="userId" placeholder="DIGITE SEU ID" required
                            class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-5 py-4 text-sm font-bold focus:border-brand-blue focus:ring-0 transition-all outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-brand-blue/60 ml-1">Senha B2</label>
                        <input type="password" id="password" placeholder="••••••••" required
                            class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-5 py-4 text-sm font-bold focus:border-brand-blue focus:ring-0 transition-all outline-none">
                    </div>

                    <button type="submit" 
                        class="w-full bg-brand-blue hover:bg-black text-white py-4 rounded-2xl font-black uppercase italic tracking-widest text-xs transition-all shadow-lg active:scale-95 flex items-center justify-center gap-3">
                        Autenticar Acesso
                        <i class="fa-solid fa-arrow-right-long text-[#FEDB00]"></i>
                    </button>
                    
                    <p id="errorLabel" class="text-center text-red-500 text-[10px] font-black uppercase hidden animate-pulse">
                        Credenciais Inválidas!
                    </p>
                </form>
            </div>
        </div>
    </main>

    <div id="modalSucesso" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-brand-blue/60 backdrop-blur-sm animate-fadeIn"></div>
        <div class="relative bg-white rounded-[2.5rem] p-8 shadow-2xl w-full max-w-sm mx-4 transform transition-all animate-bounceIn border-4 border-[#FEDB00]">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-check text-4xl text-green-500 animate-scaleCheck"></i>
                </div>
                <h3 class="text-2xl font-black italic uppercase text-brand-blue tracking-tighter mb-2">Acesso Liberado!</h3>
                <p class="text-gray-500 text-sm font-bold mb-8">Sincronizando com o CureTire Flow...</p>
                <button onclick="proceedToDashboard()" 
                        class="w-full bg-brand-blue hover:bg-black text-white py-4 rounded-2xl font-black uppercase italic tracking-widest text-xs transition-all shadow-lg active:scale-95">
                    Entrar no Sistema
                </button>
            </div>
        </div>
    </div>

    <footer class="bg-brand-blue text-white border-t-2 border-[#FEDB00] mt-auto">
        <div class="max-w-7xl mx-auto px-6 py-4 text-center">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex flex-col md:flex-row items-center gap-2 md:gap-4">
                    <span class="text-[#FEDB00] italic font-black text-xl tracking-tighter uppercase">Goodyear</span>
                    <span class="text-white font-medium tracking-widest text-xs uppercase opacity-80 italic">CureTire Flow</span>
                </div>
                <p class="text-[9px] text-gray-400 font-medium uppercase tracking-wider italic">
                    &copy; 2026 Goodyear Tire & Rubber Co. | Unidade B2
                </p>
            </div>
        </div>
    </footer>

    <script>
        const loginForm = document.getElementById('loginForm');
        const errorLabel = document.getElementById('errorLabel');

        loginForm.addEventListener('submit', (event) => {
            handleLoginAttempt(event);
        });

        function handleLoginAttempt(event) {
            event.preventDefault();
            const userId = document.getElementById('userId').value;
            const password = document.getElementById('password').value;

            // Simulação de login
            if (userId.length >= 3 && password === "123456") {
                showSuccessModal();
            } else {
                showErrorMessage();
            }
        }

        function showSuccessModal() {
            const modal = document.getElementById('modalSucesso');
            modal.classList.remove('hidden');
            const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
            audio.volume = 0.2;
            audio.play();
        }

        function showErrorMessage() {
            errorLabel.classList.remove('hidden');
            setTimeout(() => errorLabel.classList.add('hidden'), 3000);
        }

        function proceedToDashboard() {
            window.location.href = "index.php";
        }
    </script>
</body>
</html>