// Call the dataTables jQuery plugin
let table;
$(document).ready(function () {
    table = $('#dataTable').DataTable();
});

function renderMessage(message, type = 'success') {
    $(".alert").alert();
    const alertMessage = $('#alert-message');

    alertMessage.removeClass('alert-success');
    alertMessage.removeClass('alert-danger');
    if (type === 'error') {
        alertMessage.addClass('alert-danger');
        alertMessage.css('display', 'block')
        alertMessage.text(message);
    } else {
        alertMessage.addClass('alert-success');
        alertMessage.css('display', 'block');
        alertMessage.text(message);
    }

    setTimeout(closeAlert, 3000);
}

function closeAlert() {
    $(".alert").css('display', 'none');
}