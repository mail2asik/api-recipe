/* Simulate Ajax call on Panel with reload effect */
function blockUI(item) {
    $(item).block({
        message: '<svg class="circular"><circle class="path" cx="40" cy="40" r="10" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg>',
        css: {
            border: 'none',
            width: '14px',
            backgroundColor: 'none'
        },
        overlayCSS: {
            backgroundColor: '#fff',
            opacity: 0.6,
            cursor: 'wait'
        }
    });
}

function unblockUI(item) {
    $(item).unblock();
}
