// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.togglePassword').forEach(function(toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            this.classList.toggle('ph-eye');
            this.classList.toggle('ph-eye-slash');
            const input = this.previousElementSibling;
            if (input && input.tagName === 'INPUT') {
                if (input.type === 'password') {
                    input.type = 'text';
                } else {
                    input.type = 'password';
                }
            }
        });
    });
});

// Global Functions
function buttonState(button, state, initialText = null) {
    let buttonElement;
    
    if (typeof button === 'string') {
        buttonElement = document.querySelector(button);
    } else {
        buttonElement = button;
    }

    if (!buttonElement) return;

    if (state === 'loading') {
        buttonElement.disabled = true;
        buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    } else {
        buttonElement.disabled = false;
        if (initialText) {
            buttonElement.innerHTML = initialText;
        }
    }
}

function underDevelopment(event) {
    // prevent default action
    event.preventDefault();

    Swal.fire({
        title: 'Under Development',
        text: 'This feature is under development and will be available soon.',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function confirmAction(event, isXhr = false) {
    event.preventDefault();
    
    const target = event.target;
    const icon = target.dataset.mode || 'error';
    const href = target.href || target.dataset.href;
    const message = target.dataset.message || 'Are you sure you want to proceed?';

    // determine confirmation button color based on icon
    let confirmButtonColor;
    switch (icon) {
        case 'success': confirmButtonColor = '#28a745'; break;
        case 'info': confirmButtonColor = '#17a2b8'; break;
        case 'warning': confirmButtonColor = '#ffc107'; break;
        default: confirmButtonColor = '#6c757d'; break;
    }

    Swal.fire({
        title: 'Confirm Action',
        text: message,
        icon: icon,
        showCancelButton: true,
        confirmButtonText: 'Yes, proceed',
        cancelButtonText: 'No, cancel',
        confirmButtonColor: confirmButtonColor,
    }).then((result) => {
        if (result.isConfirmed) {
            if (isXhr) {
                fetch(href, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        title: 'Success',
                        text: data.message,
                        icon: 'success'
                    });
                })
                .catch(error => {
                    toast.error({
                        message: error.message || 'An error occurred while processing your request.'
                    });
                });
            } else {
                window.location.href = href;
            }
        }
    });
}


// Styles and Scripts Injection
function injectStylesheet(url) {
    var link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = url;
    document.head.appendChild(link);
}

function injectScript(url) {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = url;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

function copyToClipboard(text) {
    var input = document.createElement('input');
    input.value = text;
    document.body.appendChild(input);
    input.select();
    document.execCommand('copy');
    document.body.removeChild(input);
}

async function submitForm(event) {
    event.preventDefault();

    const form = event.target;
    const isMultipart = form.enctype === 'multipart/form-data';
    let formData = isMultipart ? new FormData(form) : new URLSearchParams(new FormData(form));

    // Retrieve preRequestHandler and postRequestHandler from data attributes
    const preRequestHandler = form.dataset.preRequest;
    const postRequestHandler = form.dataset.postRequest;
    
    const submitButton = form.querySelector('button[type="submit"]');
    const buttonLabel = submitButton ? submitButton.innerHTML : null;
    if (submitButton) {
        buttonState(submitButton, 'loading');
    }

    if (preRequestHandler && typeof window[preRequestHandler] === 'function') {
        const preRequestResult = await window[preRequestHandler]();
        if (preRequestResult === false) {
            // Reset button state if pre-request handler fails
            if (submitButton) {
                buttonState(submitButton, 'reset', buttonLabel);
            }
            return; // Stop form submission if preRequestHandler doesn't return true
        }
    }

    const headers = {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
    };

    if (!isMultipart) {
        headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
    }

    try {
        const response = await fetch(form.action, {
            method: form.method || 'POST',
            body: formData,
            headers: headers
        });

        const data = await response.json();

        if (postRequestHandler && typeof window[postRequestHandler] === 'function') {
            window[postRequestHandler](data);
        } else {
            if (data.status) {
                toast.success({ message: data.message });
            } else {
                toast.error({ message: data.message || 'request returned an empty response' });
            }
        }
        
        if (data.redirect) {
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        }

    } catch (error) {
        if (!navigator.onLine) {
            let response = { status: false, message: 'Cannot connect to the server' };
            if (postRequestHandler && typeof window[postRequestHandler] === 'function') {
                window[postRequestHandler](response);
            } else {
                toast.error({ message: response.message });
            }
        } else {
            try {
                const errorResponse = await error.response?.json();
                if (errorResponse) {
                    if (postRequestHandler && typeof window[postRequestHandler] === 'function') {
                        window[postRequestHandler](errorResponse);
                        return;
                    } else {
                        toast.error({ message: errorResponse.message || 'Unknown server error occurred, malformed response returned' });
                        return;
                    }
                }

                let response = { status: false, message: 'Unknown Server Error Occurred' };
                if (postRequestHandler && typeof window[postRequestHandler] === 'function') {
                    window[postRequestHandler](response);
                } else {
                    toast.error({ message: response.message });
                }
                
            } catch (e) {
                let response = { status: false, message: 'Unknown Server Error Occurred' };
                if (postRequestHandler && typeof window[postRequestHandler] === 'function') {
                    window[postRequestHandler](response);
                } else {
                    toast.error({ message: response.message });
                }
            }
        }
    } finally {
        if (submitButton) {
            buttonState(submitButton, 'reset', buttonLabel);
        }
    }
}


document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.togglePassword').forEach(function(toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            this.classList.toggle('ph-eye');
            this.classList.toggle('ph-eye-slash');
            const input = this.previousElementSibling;
            if (input && input.tagName === 'INPUT') {
                if (input.type === 'password') {
                    input.type = 'text';
                } else {
                    input.type = 'password';
                }
            }
        });
    });

    // Listen to all .fs-* classes and assign font-size dynamically
    document.querySelectorAll('[class*="fs-"]').forEach(function(element) {
        const classes = element.className.split(' ');
        const fontSizeClass = classes.find(c => c.startsWith('fs-'));
        if (fontSizeClass) {
            const size = fontSizeClass.split('-')[1];
            element.style.fontSize = size + 'px';
        }
    });

    // Handle system messages
    const systemMessage = document.getElementById('system-message');
    if (systemMessage) {
        const messages = systemMessage.querySelectorAll('p');
        messages.forEach(function(messageElement) {
            const type = messageElement.dataset.type;
            const text = messageElement.textContent;
            
            if (type === 'success') {
                toast.success({ message: text });
            } else if (type === 'error') {
                toast.error({ message: text });
            } else if (type === 'warning') {
                toast.warning({ message: text });
            } else {
                toast.info({ message: text });
            }
        });
        systemMessage.remove();
    }
});
