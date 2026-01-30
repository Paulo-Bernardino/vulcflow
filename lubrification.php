<?php include_once "header.html"; ?>

<main class="flex-1 p-4 md:p-8 max-w-lg mx-auto w-full animate-fadeIn">
    
    <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100">
        
        <div class="bg-brand-blue p-8 text-white relative">
            <div class="absolute top-0 right-0 p-6 opacity-10">
                <i class="fas fa-oil-can text-6xl"></i>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-yellow-400 mb-1">Check-List </p>
                <h2 class="text-3xl font-black italic uppercase tracking-tighter flex items-center gap-3">
                    <i class="fas fa-droplet"></i> Lubrificação
                </h2>
            </div>
        </div>

        <div class="p-8">
            <form id="formLubrificacao" action="#" method="POST" class="space-y-8">
                
                <div class="bg-gray-50 p-6 rounded-[2rem] border-2 border-dashed border-gray-200 relative group focus-within:border-brand-blue transition-all">
                    <span class="absolute -top-3 left-6 bg-white px-3 py-1 text-[9px] font-black text-brand-blue uppercase tracking-widest border-2 border-gray-100 rounded-full">
                        <i class="fas fa-lock mr-1"></i> Dados Fixos
                    </span>
                    
                    <div class="grid grid-cols-1 gap-6 mt-2">
                        <div>
                            <label for="data_validade" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Data de Validade</label>
                            <input type="date" id="data_validade" name="data_validade" required 
                                   class="block w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl text-sm font-black text-brand-blue focus:border-brand-blue outline-none transition-all">
                        </div>

                        <div>
                            <label for="linha" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Linha de Produção</label>
                            <div class="relative">
                                <select id="linha" name="linha" required 
                                        class="block w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl text-sm font-black text-brand-blue focus:border-brand-blue outline-none appearance-none cursor-pointer">
                                    <option value="" disabled selected>Selecione...</option>
                                    <?php foreach(range('N', 'T') as $letra): ?>
                                        <option value="<?= $letra ?>">Linha <?= $letra ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-brand-blue pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 pt-2">
                    <div>
                        <label for="cavidade" class="block text-[10px] font-black text-brand-blue uppercase tracking-widest mb-2 ml-1">Identificação da Cavidade</label>
                        <div class="relative">
                            <i class="fas fa-layer-group absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="text" id="cavidade" name="cavidade" required placeholder="EX: C01"
                                   class="block w-full pl-12 pr-5 py-4 bg-white border-2 border-gray-100 rounded-2xl text-sm font-black text-brand-blue focus:border-brand-blue outline-none transition-all uppercase placeholder:font-normal placeholder:text-gray-300">
                        </div>
                    </div>

                    <div>
                        <label for="barcode" class="block text-[10px] font-black text-brand-blue uppercase tracking-widest mb-2 ml-1">Leitura de Barcode</label>
                        <div class="relative">
                            <i class="fas fa-barcode absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="text" id="barcode" name="barcode" required placeholder="Aguardando leitura..."
                                   class="block w-full pl-12 pr-5 py-4 bg-brand-blue/5 border-2 border-brand-blue/20 rounded-2xl text-sm font-black text-brand-blue focus:border-brand-blue outline-none transition-all placeholder:text-brand-blue/30">
                        </div>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full group flex items-center justify-center gap-3 py-5 bg-brand-blue hover:bg-black text-white rounded-2xl shadow-xl shadow-blue-900/10 transition-all active:scale-[0.98]">
                    <span class="font-black italic uppercase tracking-[0.2em] text-xs">Confirmar Lubrificação</span>
                    <i class="fas fa-check-circle text-yellow-400 group-hover:rotate-12 transition-transform"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="mt-8 text-center">
        <a href="index.php" 
           class="inline-flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-brand-blue transition-colors group">
            <i class="fas fa-chevron-left group-hover:-translate-x-1 transition-transform"></i> 
            Voltar ao Dashboard
        </a>
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formLubrificacao');
    const inputData = document.getElementById('data_validade');
    const selectLinha = document.getElementById('linha');
    const inputCavidade = document.getElementById('cavidade');
    const inputBarcode = document.getElementById('barcode');

    inputData.value = localStorage.getItem('lub_data') || new Date().toISOString().split('T')[0];
    selectLinha.value = localStorage.getItem('lub_linha') || "";

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        localStorage.setItem('lub_data', inputData.value);
        localStorage.setItem('lub_linha', selectLinha.value);

        if (typeof mostrarSucesso === "function") {
            mostrarSucesso();
        }

        inputCavidade.value = '';
        inputBarcode.value = '';
        
        inputCavidade.focus();
    });

    inputCavidade.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    inputCavidade.focus();
});
</script>

<?php include_once "footer.html"; ?>