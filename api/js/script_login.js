// Login
// Alternar entre Login e Redefinir Senha
document.addEventListener('DOMContentLoaded', function () {
    const forgotPasswordLink = document.getElementById('forgot-password');
    const backToLoginLink = document.getElementById('back-to-login');
    const loginForm = document.getElementById('login-form');
    const resetForm = document.getElementById('reset-form');
    const formTitle = document.getElementById('form-title');

    // Verifique se os elementos estão presentes
    console.log('Esqueci minha senha:', forgotPasswordLink);
    console.log('Voltar para o login:', backToLoginLink);
    console.log('Formulário de login:', loginForm);
    console.log('Formulário de redefinição:', resetForm);
    console.log('Título do formulário:', formTitle);

    if (forgotPasswordLink && backToLoginLink && loginForm && resetForm && formTitle) {
        // Função para alternar para o formulário de redefinição de senha
        function showResetForm() {
            loginForm.style.display = 'none'; // Oculte o formulário de login
            resetForm.style.display = 'block'; // Mostre o formulário de redefinição
            formTitle.textContent = '_____ Esqueceu sua senha? _____'; // Altere o título
        }

        // Função para voltar ao formulário de login
        function showLoginForm() {
            resetForm.style.display = 'none'; // Oculte o formulário de redefinição
            loginForm.style.display = 'block'; // Mostre o formulário de login
            formTitle.textContent = '______ Acesse sua conta ______'; // Restaura o título
        }

        // Ao clicar em "Esqueci minha senha"
        forgotPasswordLink.addEventListener('click', function (event) {
            event.preventDefault(); // Previne o comportamento padrão do link
            showResetForm(); // Chama a função para mostrar o formulário de redefinição
        });

        // Ao clicar em "Voltar para o login"
        backToLoginLink.addEventListener('click', function (event) {
            event.preventDefault(); // Previne o comportamento padrão do link
            showLoginForm(); // Chama a função para mostrar o formulário de login
        });
    } else {
        console.error("Um ou mais elementos necessários não foram encontrados no DOM.");
    }
});

// Garantir que as senhas digitadas correspondam antes de submeter o formulário.
document.addEventListener('DOMContentLoaded', function () {
    const resetForm = document.getElementById('reset-password-form');
    const newPasswordInput = document.getElementById('new-password');
    const confirmPasswordInput = document.getElementById('confirm-password');

    // Verifique se os elementos estão presentes antes de adicionar o evento
    if (resetForm && newPasswordInput && confirmPasswordInput) {
        resetForm.addEventListener('submit', function (event) {
            if (newPasswordInput.value !== confirmPasswordInput.value) {
                event.preventDefault(); // Prevenir envio do formulário
                alert('As senhas não correspondem. Tente novamente.');
            }
        });
    } else {
        console.error("Um ou mais elementos do formulário de redefinição não foram encontrados no DOM.");
    }
});
