const idiframe = document.currentScript.getAttribute('who')

function retry() {

    setTimeout(() => {
        const iframe = document.getElementById(idiframe)
        if (!iframe) retry()
        window.addEventListener('message', (evt) => {
            if (evt.source &&
                evt.data.length > 0) {
                try {
                    var msg = JSON.parse(evt.data);

                    if (msg != null) {
                        if (msg.event == 'load') {
                            const iframe = document.getElementById(idiframe)
                            setTimeout(() => {
                                iframe.style.pointerEvents = 'none';
                            }, 100);
                        }

                    }
                }
                catch (e) {
                    console.error(e);
                }
            }
        });

    }, 1000)
}

retry()
