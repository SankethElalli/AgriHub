<?php
include ('fsession.php');
ini_set('memory_limit', '-1');

if(!isset($_SESSION['farmer_login_user'])){
header("location: ../index.php");}
$query4 = "SELECT * from farmerlogin where email='$user_check'";
              $ses_sq4 = mysqli_query($conn, $query4);
              $row4 = mysqli_fetch_assoc($ses_sq4);
              $para1 = $row4['farmer_id'];
              $para2 = $row4['farmer_name'];
			  
?>

<!DOCTYPE html>
<html>
<?php require ('fheader.php');  ?>

<head>
	<link rel="stylesheet" href="../assets/css/creativetim.min.css" type="text/css">
	<link rel="stylesheet" href="../assets/css/custom.css" type="text/css">
	<link rel="stylesheet" href="../assets/css/footer.css" type="text/css">

</head>

<style>
.chat-box {
    height: 65vh;
    overflow-y: auto;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 15px;
    box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
}

.message {
    margin-bottom: 15px;
    padding: 15px;
    border-radius: 15px;
    display: inline-block;
    max-width: 80%;
    word-wrap: break-word;
    white-space: normal;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    position: relative;
    clear: both;
}

.left-side {
    background-color: #E1FFBB;
    float: left;
    margin-right: 50px;
}

.right-side {
    background-color: #008641;
    color: white;
    float: right;
    margin-left: 50px;
}

.popup {
    position: fixed;
    bottom: 20vh;
    left: 50%;
    transform: translateX(-50%);
    background-color: #008641;
    color: white;
    border-radius: 8px;
    padding: 12px 24px;
    font-size: 16px;
    display: none;
    z-index: 1000;
    animation: fadeInOut 1s ease;
}

.chat-header {
    background: linear-gradient(45deg, #006633, #00994d);
    padding: 20px;
    border-radius: 15px 15px 0 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.chat-header img {
    width: 40px;
    height: 40px;
    margin-right: 15px;
}

.chat-footer {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 0 0 15px 15px;
    border-top: 1px solid #e9ecef;
}

.action-buttons {
    display: flex;
    gap: 10px;
}

.action-buttons button {
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 500;
}

#userInput {
    border-radius: 25px;
    padding: 12px 20px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

#userInput:focus {
    border-color: #008641;
    box-shadow: 0 0 0 0.2rem rgba(0,134,65,0.25);
}

#sendButton {
    border-radius: 25px;
    padding: 12px 30px;
    background: #008641;
    border: none;
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
}

#sendButton:hover {
    background: #006633;
    transform: translateY(-2px);
}

.far.fa-clipboard {
    cursor: pointer;
    padding: 5px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.far.fa-clipboard:hover {
    background: #E1FFBB;
    color: #008641;
}

@keyframes fadeInOut {
    0% { opacity: 0; }
    20% { opacity: 1; }
    80% { opacity: 1; }
    100% { opacity: 0; }
}
</style>

<body class="bg-white" id="top">
<?php include ('fnav.php');  ?>
<section class="section section-shaped section-lg">
    <div class="shape shape-style-1 shape-primary">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card text-white bg-gradient-white mt--6">
                    <div class="chat-header">
                        <div class="d-flex align-items-center">
                            <img src="../assets/img/Mai.png" class="" alt="Mistral AI Logo">
                            <h4 class="text-white mb-0"> Assistant for AgriHub</h4>
                        </div>
                        <div class="action-buttons">
                            <button class="btn btn-light" onclick="window.print()">
                                <i class="fas fa-print mr-2"></i>Print
                            </button>
                            <button class="btn btn-danger" onclick="clearContent()">
                                <i class="fas fa-trash mr-2"></i>Clear Chat
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="chat-box" id="chatbox">
                            <span id="copy-popup" class="popup">Copied!</span>
                        </div>
                    </div>

                    <div class="chat-footer">
                        <div class="form-group mb-0">
                            <div class="input-group">
                                <input id="userInput" type="text" class="form-control" 
                                    placeholder="Type your message here...">
                                <div class="input-group-append">
                                    <button id="sendButton" class="btn btn-success">
                                        <i class="fas fa-paper-plane mr-2"></i>Send
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require("footer.php");?>
<script>
function clearContent(){
    document.getElementById('chatbox').innerHTML = '<span id="copy-popup" class="popup">Copied!</span>';
    messages = [{
        "role": "system",
        "content": "You are a helpful agricultural assistant powered by Mistral Small. Provide farmers with accurate and helpful information about farming practices, crop management, pest control, and agricultural technologies. Be concise and practical in your advice."
    }];
}
	
const url = new URL(window.location.href);
const apiKey = "VusXb6eOuRxEOnVtkbXpadsMrtNXq9Jg";   // Your Mistral API key
const chatbox = $("#chatbox");
const userInput = $("#userInput");
const sendButton = $("#sendButton");
let messages = [{
    "role": "system",
    "content": "You are a helpful agricultural assistant created by Mistral AI. Provide farmers with accurate and helpful information about farming practices, crop management, pest control, and agricultural technologies. Be concise and practical in your advice."
}];

sendButton.on("click", () => {
    const message = userInput.val();
    if (message) {
        messages.push({
            "role": "user",
            "content": message
        });
		const displaytext = window.markdownit().render(message);
		let userMessageHtml = '<pre><div class="message right-side "  >' + displaytext + '</div></pre>';
		chatbox.append(userMessageHtml);
		chatbox.animate({ scrollTop: 20000000 }, "slow");
        userInput.val("");
        sendButton.val("Generating Response..");
		sendButton.prop("disabled", true);
        fetchMessages();
    }
});


userInput.on("keydown", (event) => {
    if (event.keyCode === 13 && !event.ctrlKey && !event.shiftKey) {
        event.preventDefault();
        sendButton.click();
    } else if (event.keyCode === 13 && (event.ctrlKey || event.shiftKey)) {
        event.preventDefault();
        const cursorPosition = userInput.prop("selectionStart");
        const currentValue = userInput.val();

        userInput.val(
            currentValue.slice(0, cursorPosition) +
            "\n" +
            currentValue.slice(cursorPosition)
        );
        userInput.prop("selectionStart", cursorPosition + 1);

        userInput.prop("selectionEnd", cursorPosition + 1);
    }
});

function fetchMessages() {
        var settings = {
            url: "https://api.mistral.ai/v1/chat/completions",
            method: "POST",
            timeout: 0,
            headers: {
                "Authorization": "Bearer " + apiKey,
                "Content-Type": "application/json"
            },
            data: JSON.stringify({
                model: "mistral-small", // Changed from mistral-medium to mistral-small
                messages: messages,
                temperature: 0.7,       // Controls randomness: 0.0 is deterministic, higher values are more random
                max_tokens: 1000,       // Maximum number of tokens to generate
                top_p: 0.95,            // Alternative to temperature for nucleus sampling
                safe_prompt: true       // Optional safety setting to filter harmful content
            })
        };
        $.ajax(settings).done(function(response) {
            const message = response.choices[0].message;
            messages.push({
                "role": message.role,
                "content": message.content
            });
			const htmlText = window.markdownit().render(message.content);
			const botMessageHtml = '<pre><div class="message left-side" id="' + CryptoJS.MD5(htmlText) + '">' + htmlText + '</div><i class="far fa-clipboard ml-1 btn btn-outline-dark" id="' + CryptoJS.MD5(htmlText) + '-copy"></i></pre>';             

            chatbox.append(botMessageHtml);	

			// Add event listener to the copy icon 
			var copyIcon = document.getElementById(CryptoJS.MD5(htmlText) + '-copy'); 
			var copyText = document.getElementById(CryptoJS.MD5(htmlText));

			copyIcon.addEventListener("click", function() {
			  var tempTextarea = document.createElement("textarea");
			  tempTextarea.value = copyText.textContent;
			  document.body.appendChild(tempTextarea);
			  tempTextarea.select();
			  document.execCommand("copy");
			  document.body.removeChild(tempTextarea);
			  
			  // Display "Copied!" popup
			  var copyPopup = document.getElementById("copy-popup");
			  copyPopup.style.display = "block";
			  setTimeout(function() {
				copyPopup.style.display = "none";
			  }, 1000); // Display for 1 second
			});
			
			chatbox.animate({ scrollTop: 20000000 }, "slow");
            sendButton.val("SUBMIT");
            sendButton.prop("disabled", false);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            sendButton.val("Error");
            let errorMessage = "Failed to fetch server response";
            
            if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                // Mistral AI specific error format
                errorMessage += ": " + jqXHR.responseJSON.error.message;
            } else if (jqXHR.status === 401) {
                errorMessage = "Authentication error: Please check your API key";
            } else if (jqXHR.status === 429) {
                errorMessage = "Rate limit exceeded: Too many requests";
            } else {
                errorMessage += ": " + errorThrown;
            }
            
            let errorHtml = '<pre><div class="message left-side text-danger">' + errorMessage + '</div></pre>';
            chatbox.append(errorHtml);
            chatbox.animate({ scrollTop: 20000000 }, "slow");
        });
    }
 </script>
  
</body>
</html>