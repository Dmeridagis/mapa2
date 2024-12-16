document.addEventListener('DOMContentLoaded', function() {
    const formEditarPerfil = document.getElementById('formEditarPerfil');
    const btnEditar = document.getElementById('btnEditar');
    const btnGuardar = document.getElementById('btnGuardar');
    const emailInput = document.getElementById('email');
    const direccionInput = document.getElementById('direccion');

    function habilitarEdicion() {
        emailInput.disabled = false;
        direccionInput.disabled = false;
        btnEditar.style.display = 'none';
        btnGuardar.style.display = 'inline-block';
    }

    btnEditar.addEventListener('click', habilitarEdicion);

    formEditarPerfil.addEventListener('submit', function(e) {
        e.preventDefault();
        emailInput.disabled = true;
        direccionInput.disabled = true;
        btnEditar.style.display = 'inline-block';
        btnGuardar.style.display = 'none';
        // Aquí iría la lógica para guardar los cambios en el servidor
        alert('Perfil actualizado correctamente');
    });

    const tiprecla1 = document.querySelector('.tiprecla1');
    const tiprecla2 = document.querySelector('.tiprecla2');
    const tiprecla3 = document.querySelector('.tiprecla3');
    const reclamosContainer = document.querySelector('.reclamos-container');

    function cambiarPestana(pestana) {
        tiprecla1.classList.remove('active');
        tiprecla2.classList.remove('active');
        tiprecla3.classList.remove('active');
        pestana.classList.add('active');

        // Aquí iría la lógica para cargar los reclamos correspondientes
        // Por ahora, solo cambiaremos el texto mostrado
        let mensaje = '';
        switch(pestana) {
            case tiprecla1:
                mensaje = 'Mostrando reclamos enviados';
                break;
            case tiprecla2:
                mensaje = 'Mostrando reclamos en proceso';
                break;
            case tiprecla3:
                mensaje = 'Mostrando reclamos resueltos';
                break;
        }
        reclamosContainer.innerHTML = `<p>${mensaje}</p>`;
    }

    tiprecla1.addEventListener('click', function() { cambiarPestana(tiprecla1); });
    tiprecla2.addEventListener('click', function() { cambiarPestana(tiprecla2); });
    tiprecla3.addEventListener('click', function() { cambiarPestana(tiprecla3); });

    // Inicializar con la primera pestaña activa
    cambiarPestana(tiprecla1);
});