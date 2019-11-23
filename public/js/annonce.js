$('#add-image').click(function () {
    const index = +$('#widgets-counter').val(); // {# On récupère le n° des champs qu'on veut créer #}

        // {# On récupère le code prototype des entrées#}
        const tmpl = $('#annonce_images').data('prototype').replace(/_name_/g, index);

        $('#widgets-counter').val(index + 1);

        // {# On injecte le blog dans la div #}

        $('#annonce_images').append(tmpl);

        handleDeleteButtons();

    });
function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function() {
        const target = this.dataset.target;
        $(target).remove();
    });

}

// Comme on est sur la modification du formulaire il ne faut pas que #widgets-counter soit initialiser à 0 !

function updateCounter() {
    const count = +$('#annonce_images .form-group').length;

    $('#widgets-counter').val(count);
}

updateCounter();