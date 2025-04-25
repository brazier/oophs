<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <title>OPEN Open Peak heartbeat server</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.30.0/themes/prism-tomorrow.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js" integrity="sha512-7Pi/otdlbbCR+LnW+F7PwFcSDJOuUJB3OxtEHbg4vSMvzvJjde4Po1v4BR9Gdc9aXNUNFVUY+SK51wWT8WF0Gg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="script.js" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.30.0/prism.min.js" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.30.0/plugins/unescaped-markup/prism-unescaped-markup.min.js" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/components/prism-bash.min.js" integrity="sha512-35RBtvuCKWANuRid6RXP2gYm4D5RMieVL/xbp6KiMXlIqgNrI7XRUh9HurE8lKHW4aRpC0TZU3ZfqG8qmQ35zA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

    <?php
    if (isset($_GET['mac'])):
        $mac=strtolower($_GET['mac']);
        $time=date("U");
        ?>

        <div class="container-fluid">
            <div class="row justify-content-between">
                <div class="col-6 align-self-center" id="heartbeat">
                    <a class="btn disabled"><i class="fa-solid"> </i> Device: <?php echo $mac ?> last active <span>16.03.53 - 00:00</span></a>
                </div>


                <div class="col-2 text-end">
                    <h1 class="bd-title">OOPHS</h1>
                    <h6 class="text-body-secondary">OPEN Open Peak Heartbeat Server</h6>
                </div>

            </div>
            <div class="row mt-2">
                <div class="col">
                    <div class="row justify-content-between">
                        <div class="col-1 align-self-end"> Input</div>
                        <div class="col text-end">
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <a class="btn btn-outline-secondary" onclick="addText('telnet')"><i class="fa-solid fa-terminal"></i> Activate telnet</a>
                                <a class="btn btn-outline-secondary" onclick="addText('details')"><i class="fa-solid fa-laptop-code"></i> Get device details</a>
                                <a class="btn btn-outline-secondary" onclick="addText('cmd');"><i class="fa-solid fa-code"></i> Send command</a>

                                <div class="btn-group">
                                    <a class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa-solid fa-square-caret-down"></i>  More
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item"><i class="fa-solid fa-play"></i> Play media</a>
                                        <a class="dropdown-item"><i class="fa-solid fa-download"></i> Firmware download</a>
                                        <a class="dropdown-item"><i class="fa-solid fa-message"></i> Publish message</a>
                                        <a class="dropdown-item" href="#"><i class="fa-solid fa-file-lines"></i> Motd</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="code-editor" id="cmd">
                        <textarea></textarea>
                        <pre><code class="language-bash"></code></pre>
                    </div>
                    <pre class="language-markup"><code id="editable"></code></pre>
                </div>
                <div class="col-1 align-self-center text-center">
                    <div>
                        <a class="btn btn-success disabled" onclick="sendData()" id="sendBtn">
                            <i class="fa-solid fa-right-left"></i>  Send</a>
                        </div>
                    </div>
                    <script type="text/javascript">
                        mac = '<?php echo $mac ?>';
                        var editor = document.getElementById('cmd').querySelector("textarea"),
                        visualizer = document.getElementById('cmd').querySelector("code");

                        editor.addEventListener("input", (e) => {
                            visualizer.innerHTML = e.target.value;
                            var element = document.getElementById('editable');
                            var addElement = document.getElementById('cmd').querySelector('code');
                            element.innerHTML = '<!--'+contentStart+addElement.innerHTML+contentEnd+'-->';
                            sh = "";
                            sh = addElement.innerHTML;
                            Prism.highlightAll();
                        })

                    </script>

                    <div class="col me-2">
                        <div class="row justify-content-between">
                            <div class="col p-0 align-self-end"> Response

                                <span class="text-success-emphasis fw-light" id="timestamp">
                                    Waiting for reply...
                                </span></div> <div class="col p-0 text-end"><a class="btn btn-outline-info" onclick="fetchReply('XML');fetchReply('timestamp');fetchReply('heartbeat')"><i class="fa-solid fa-rotate"></i> Refresh</a></div>

                                <pre class="language-markup"><code id="XML"><div class="d-flex justify-content-center align-items-center" style="height:100%;"><i class="fa-solid fa-spinner fa-4x fa-spin text-info-emphasis"></i></div></code></pre> 
                            </div>
                        </div>
                    <?php else: ?>

                        <main class="form-signin w-100 m-auto">
                            <form action="" method="GET">
                                <span style="font-size: 48px; color: Dodgerblue;"><i class="fa-solid fa-file-circle-question fa-fade"></i></span><span class="h3 mb-3 fw-normal">  Check device</span>


                                <div class="form-floating">
                                    <input type="text" class="form-control" name ="mac" id="floatingInput" placeholder="de:ad:ca:fe:ba:be">
                                    <label for="floatingInput">Device MAC address</label>
                                </div>
                                <button class="btn btn-primary w-100 py-2" type="submit">Check Device</button>
                            </form>
                        </main>
                    <?php endif; ?>
</body>
</html>