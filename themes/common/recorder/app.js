//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;

var gumStream; 						//stream from getUserMedia()
var rec; 							//Recorder.js object
var input; 							//MediaStreamAudioSourceNode we'll be recording

// shim for AudioContext when it's not avb. 
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext //audio context to help us record

var recordButton = document.getElementById("recordButton");
var mouseIsDown = false;
var started = false;
recordButton.addEventListener('mousedown', function(event) {
    // simulating hold event
    mouseIsDown = true;
    setTimeout(function() {

        if (mouseIsDown) {
            recordButton.style.color = 'red';
            startRecording();
            started = true;
        }
    }, 300);
});

recordButton.addEventListener('mouseup', function(event) { 
  // simulating hold event
   mouseIsDown = false;
   recordButton.style.color = 'inherit';
   if(started){
   stopRecording();
   started = false;
}
});
var recordingsList = document.getElementById('send-area');
var mainBlob;

function startRecording() {
	console.log("recordButton clicked");

	/*
		Simple constraints object, for more advanced audio features see
		https://addpipe.com/blog/audio-constraints-getusermedia/
	*/
    
    var constraints = { audio: true, video:false }

 	/*
    	Disable the record button until we get a success or fail from getUserMedia() 
	*/

	// recordButton.disabled = true;
	// stopButton.disabled = false;

	/*
    	We're using the standard promise based getUserMedia() 
    	https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
	*/

	navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
		console.log("getUserMedia() success, stream created, initializing Recorder.js ...");

		/*
			create an audio context after getUserMedia is called
			sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
			the sampleRate defaults to the one set in your OS for your playback device

		*/
		audioContext = new AudioContext();

		//update the format 
		// document.getElementById("formats").innerHTML="Format: 1 channel pcm @ "+audioContext.sampleRate/1000+"kHz"

		/*  assign to gumStream for later use  */
		gumStream = stream;
		
		/* use the stream */
		input = audioContext.createMediaStreamSource(stream);

		/* 
			Create the Recorder object and configure to record mono sound (1 channel)
			Recording 2 channels  will double the file size
		*/
		rec = new Recorder(input,{numChannels:1})

		//start the recording process
		rec.record()

		console.log("Recording started");

	}).catch(function(err) {
	  	//enable the record button if getUserMedia() fails
    	recordButton.disabled = false;
	});
}

function pauseRecording(){
	console.log("pauseButton clicked rec.recording=",rec.recording );
	if (rec.recording){
		//pause
		rec.stop();
		pauseButton.innerHTML="Resume";
	}else{
		//resume
		rec.record()
		pauseButton.innerHTML="Pause";

	}
}

function stopRecording() {
	console.log("stopButton clicked");

	//tell the recorder to stop the recording
	rec.stop();

	//stop microphone access
	gumStream.getAudioTracks()[0].stop();

	//create the wav blob and pass it on to createDownloadLink
	rec.exportWAV(createDownloadLink);
}
function uploadAudio() {
recordingsList.innerHTML = '';
    if (mainBlob == '') {
        return false;
    } else {
        var filename = 'audio_' + Date.now();
        var xhr = new XMLHttpRequest();
        xhr.onload = function(e) {
            if (this.readyState === 4) {
                console.log("Server returned: ", e.target.responseText);
                setTimeout(function () {
                	
               
                $(".myop").append(e.target.responseText);
                $(".no_msg").hide();
                 scrollB();
                  }, 700);
            }
        };
        var fd = new FormData();
        fd.append("userFile", mainBlob, filename + '.mp3');
        xhr.open("POST", UPLOAD_URL, true);
        xhr.send(fd);
    }
}
function createDownloadLink(blob) {
	mainBlob = blob;
	
	var url = URL.createObjectURL(blob);
	//var au = document.createElement('audio');

	var htmlData = `<div class="audio-temp">
	<div>
		<audio controls="" src="${url}"></audio>
	</div>
	<div style=" margin-left:10px;"><a href="javascript:cancelSend()" class="button btn-danger"><i class="fa fa-trash"></i></a></div>
	<div style="margin-left:10px;"><a href="javascript:uploadAudio()" class="button btn-success">Send</a></div>
	</div>
	`;
	//var li = document.createElement('li');
	//var link = document.createElement('a');

	//name of .wav file to use during upload and download (without extendion)
	//var filename = 'audio_' + Date.now();

	//add controls to the <audio> element
	//au.controls = true;
	//au.src = url;

	//save to disk link
	//link.href = url;
	//link.download = filename+".wav"; //download forces the browser to donwload the file using the  filename
	//link.innerHTML = "cancel";

	//add the new audio element to li
	//li.appendChild(au);
	
	//add the filename to the li
	//li.appendChild(document.createTextNode(filename+".wav "))

	//add the save to disk link to li
	//li.appendChild(link);
	
	//upload link
	// var upload = document.createElement('a');
	// upload.href="#";
	// upload.innerHTML = "Upload";
	// upload.addEventListener("click", function(event){
	// 	  var xhr=new XMLHttpRequest();
	// 	  xhr.onload=function(e) {
	// 	      if(this.readyState === 4) {
	// 	          console.log("Server returned: ",e.target.responseText);
	// 	      }
	// 	  };
	// 	  var fd=new FormData();
	// 	  fd.append("userFile",blob, filename + '.mp3');
	// 	  xhr.open("POST",UPLOAD_URL,true);
	// 	  xhr.send(fd);
	// })
	//li.appendChild(document.createTextNode (" "))//add a space in between
	//li.appendChild(upload)//add the upload link to li

	//add the li element to the ol
	recordingsList.innerHTML = htmlData;
}
function cancelSend() {
	recordingsList.innerHTML = '';
}