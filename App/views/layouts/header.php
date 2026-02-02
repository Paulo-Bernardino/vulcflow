<?php require_once __DIR__ . '/../../../Config/init.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Vulcanização B2</title>

    <base href="/vulcflow/">

    <link rel="stylesheet" href="App/assets/css/style.css">
    <link rel="stylesheet" href="App/assets/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="App/assets/js/cdn.min.js" defer></script>
    
    <style>
        [x-cloak] { display: none !important; }
        @keyframes progress {
            0% { width: 0%; }
            100% { width: 100%; }
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col font-sans" x-data="{ open: false, showLogoutModal: false }">
    <div class="hidden bg-green-600 bg-red-600 ring-green-300 ring-red-300"></div>
    <header class="bg-brand-blue border-b-2 border-[#FEDB00] shadow-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-3">
            <div class="flex items-center justify-between relative">

                <div class="hidden md:flex items-center gap-5 w-1/3">
                    <span class="text-[#FEDB00] italic font-black text-3xl tracking-tighter uppercase select-none">
                        Goodyear
                    </span>
                    <div class="h-7 w-[1px] bg-white/20"></div>
                </div>

                <div class="flex-1 text-center">
                    <h1 class="text-white font-bold text-base md:text-lg tracking-widest uppercase italic leading-none">
                        CureTire <span class="text-[#FEDB00]">Flow</span>
                    </h1>
                    <p class="text-[9px] text-blue-300 uppercase tracking-[0.2em] font-medium mt-1">
                        Vulcanização B2
                    </p>
                </div>

                <div class="flex items-center justify-end gap-4 md:w-1/3" x-data="{ userOpen: false }">
                    <div class="relative">
                        <button @click="userOpen = !userOpen" @click.away="userOpen = false"
                            class="bg-white/5 hover:bg-white/10 p-2.5 rounded-lg transition-all border border-white/10 text-white flex items-center gap-2">
                            
                            <span class="hidden lg:block text-[10px] font-bold uppercase tracking-wider text-blue-100">
                                <?= explode(' ', $_SESSION['user_nome'] ?? 'Operador')[0] ?>
                            </span>

                            <i class="fa-solid fa-user-gear text-sm"></i>
                            <i class="fa-solid fa-chevron-down text-[10px] text-[#FEDB00] transition-transform"
                                :class="userOpen ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="userOpen" x-cloak
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl py-2 border border-gray-200 z-[60]">

                            <div class="px-4 py-2 border-b border-gray-100 mb-1">
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Operador</p>
                                <p class="text-xs font-black text-brand-blue truncate">
                                    <?= $_SESSION['user_nome'] ?? 'Convidado' ?>
                                </p>
                            </div>

                            <button @click="showLogoutModal = true; userOpen = false; setTimeout(() => { window.location.href = 'home?logoff' }, 1550)"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors font-bold">
                                <i class="fa-solid fa-right-from-bracket text-xs"></i>
                                Sair do Sistema
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <div x-show="showLogoutModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center">
        <div class="absolute inset-0 bg-brand-blue/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-[2.5rem] p-8 shadow-2xl w-full max-w-sm mx-4 transform transition-all border-4 border-red-500">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fa-solid fa-power-off text-4xl text-red-500"></i>
                </div>
                <h3 class="text-2xl font-black italic uppercase text-brand-blue tracking-tighter mb-2">Sessão Encerrada</h3>
                <p class="text-gray-500 text-sm font-bold mb-8">Desconectando do CureTire Flow B2...</p>
                <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-red-500 animate-[progress_1.5s_ease-in-out_forwards]"></div>
                </div>
            </div>
        </div>
    </div>