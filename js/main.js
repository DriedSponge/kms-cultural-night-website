function BlockError(errorblock, msg,prefix){
    $(errorblock).html(`<div class="alert alert-danger" role="alert"><span><b>${prefix}</b><br><span id="error_message_text">${msg}</span></span></div>`)
}