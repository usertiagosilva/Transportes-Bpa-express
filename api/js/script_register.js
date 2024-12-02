// Função para ir para a próxima etapa
function nextStep(step) {
    // Validação da senha na etapa 2
    if (step === 2) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        if (password !== confirmPassword) {
            alert("As senhas não coincidem. Por favor, tente novamente.");
            return; // Impede de avançar para a próxima etapa
        }
    }

    // Oculta todas as etapas
    const steps = document.querySelectorAll('.form-step');
    steps.forEach((s) => s.style.display = 'none');

    // Exibe a etapa atual
    document.getElementById('step' + step).style.display = 'block';

    // Atualiza a barra de progresso
    updateProgressBar(step);
}

// Função para voltar para a etapa anterior
function previousStep(step) {
    // Oculta todas as etapas
    const steps = document.querySelectorAll('.form-step');
    steps.forEach((s) => s.style.display = 'none');

    // Exibe a etapa anterior
    document.getElementById('step' + step).style.display = 'block';

    // Atualiza a barra de progresso
    updateProgressBar(step);
}

// Inicia no primeiro passo
nextStep(1);

// Função para atualizar a barra de progresso
function updateProgressBar(step) {
    const progress = document.getElementById('progress');
    const progressSteps = document.querySelectorAll('.progress-step');

    // Atualiza a largura da barra
    const stepPercent = (step - 1) / (progressSteps.length - 1) * 100;
    progress.style.width = stepPercent + '%';

    // Atualiza a cor das etapas
    progressSteps.forEach((element, index) => {
        if (index < step) {
            element.classList.add('progress-step-active');
        } else {
            element.classList.remove('progress-step-active');
        }
    });
}

// Função de envio do formulário (final)
function submitForm() {
    alert("Formulário enviado com sucesso!");
    
}

// Aplicando máscara para telefone, CNPJ e CPF
// Formatar a entrada do usuário
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    const cnpjInput = document.getElementById('cnpj');
    const cpfInput = document.getElementById('cpf');

    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            let value = phoneInput.value.replace(/\D/g, '');
            value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
            value = value.replace(/(\d)(\d{4})$/, '$1-$2');
            phoneInput.value = value;
        });
    }

    if (cnpjInput) {
        cnpjInput.addEventListener('input', function() {
            let value = cnpjInput.value.replace(/\D/g, '');
            value = value.replace(/^(\d{2})(\d)/, '$1.$2');
            value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
            cnpjInput.value = value;
        });
    }

    if (cpfInput) {
        cpfInput.addEventListener('input', function() {
            let value = cpfInput.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            cpfInput.value = value;
        });
    }
});
