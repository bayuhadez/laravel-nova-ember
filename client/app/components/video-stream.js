import Component from '@ember/component';
import ENV from 'rcf/config/environment';

export default Component.extend({
	GetRTCIceCandidate()
	{
		window.RTCIceCandidate =
			window.RTCIceCandidate ||
			window.webkitRTCIceCandidate ||
			window.mozRTCIceCandidate ||
			window.msRTCIceCandidate;

		return window.RTCIceCandidate;
	},

	GetRTCPeerConnection()
	{
		window.RTCPeerConnection =
			window.RTCPeerConnection ||
			window.webkitRTCPeerConnection ||
			window.mozRTCPeerConnection ||
			window.msRTCPeerConnection;
		return window.RTCPeerConnection;
	},

	GetRTCSessionDescription()
	{
		window.RTCSessionDescription =
			window.RTCSessionDescription ||
			window.webkitRTCSessionDescription ||
			window.mozRTCSessionDescription ||
			window.msRTCSessionDescription;
		return window.RTCSessionDescription;
	},

	// wrapper for self.log method
	log(...args)
	{
		console.log(...args);
	},

	prepareCaller()
	{
		let self = this;

		// create a new peer connection
		let caller = new window.RTCPeerConnection();

		caller.onicecandidate = (event) => {

			if (!event.candidate) {
				return;
			}

			self.log('onicecandidate called');
			self.onIceCandidate(caller, event);
		}

		// to receive remote feed and show in remoteVideoElement
		caller.onaddstream = function (event) {
			self.onAddStream(event);
		}

		self.set('peerConnection', caller);

	},

	// send the ICE Candidate to the remote peer
	onIceCandidate(peer, event)
	{
		let self = this;

		if (event.candidate) {
			self.get('socket').emit('client-candidate', {
				candidate: event.candidate,
			});
		}

	},

	onAddStream(event)
	{
		this.log('onAddStream called', event.stream);
		this.setVideoSrc(this.get('remoteVideoElement'), event.stream);
	},

	setVideoSrc(videoElement, stream)
	{
		this.log('setting video src of ', videoElement, ' with ', stream);
		videoElement.srcObject = stream;
		videoElement.play();
	},

	getCam()
	{
		return navigator.mediaDevices.getUserMedia({
			video: true,
			audio: true
		});
	},

	actions: {
		startStreaming()
		{
			let self = this;

			self.getCam()
				.then(stream => {
					let caller = self.get('peerConnection');
					self.setVideoSrc(self.get('localVideoElement'), stream);
					self.log('after get cam ', stream, caller);
					caller.addStream(stream);
					caller.createOffer().then(function (desc) {
						caller.setLocalDescription(new RTCSessionDescription(desc));
						self.get('socket').emit('client-sdp', {
							sdp: desc,
						});
					})
				});
		},

		start()
		{
			let self = this;

			self.prepareCaller();

			// initiate connection with the socket server
			let socket = io.connect(ENV.socketUrl);

			socket.on('ipaddr', function(ipaddr) {
				self.log('server IP address is: ', ipaddr);
			});

			socket.on('created', (room, clientId) => {
				self.log('created room ', room, ' - client id ', clientId);
				self.set('isInitiator', true);
			});

			socket.on('joined', (room, clientId) =>{
				self.log('joined room ', room, ' - client id ', clientId);
				self.set('isInitiator', false);
			});

			socket.on('ready', () => {
				self.log('socket ready');
			});

			socket.on('message', (message) => {
				self.log('Client received message: ', message);
			});

			// join room
			let room = 'foo';
			socket.emit('create or join', room);

			if (location.hostname.match(/localhost|127\.0\.0/)) {
				socket.emit('ipaddr');
			}

			socket.on('log', function (array) {
				self.log.apply(self, array);
			});

			socket.on('client-sdp', function(msg) {
				self.log(msg);

				let caller = self.get('peerConnection');
				let sessionDesc = new RTCSessionDescription(msg.sdp)
				caller.setRemoteDescription(sessionDesc);
				caller.createAnswer().then(function(sdp) {
					caller.setLocalDescription(new RTCSessionDescription(sdp));
					socket.emit('client-answer', {
						sdp: sdp
					})
				})
			});

			socket.on('client-answer', function (answer) {
				let caller = self.get('peerConnection');
				caller.setRemoteDescription(new RTCSessionDescription(answer.sdp));
			});

			socket.on('client-candidate', function (msg) {
				let caller = self.get('peerConnection');
				caller.addIceCandidate(new RTCIceCandidate(msg.candidate));
			})

			this.set('socket', socket);
		},
	},

	init()
	{
		this._super(...arguments);

		// initialize all class attributes
		this.setProperties({
			'peerConnection': null,
			'localStream': null,
			// 'dataChannel': null,
			'socket': null,
			'localVideoElement': null,
			'remoteVideoElement': null,
			// 'RTCPeerConnectionConfig': {iceServers: [{urls: 'stun:stun.example.org'}]},

			/*
			'RTCPeerConnectionConfig': {
				'iceServers': [{
					'urls': 'stun:stun.l.google.com:19302'
				}, {
					'urls': 'turn:turn.bistri.com:80?transport=udp',
					'credential': 'homeo',
					'username': 'homeo',
				}, {
					'urls': 'turn:turn.bistri.com:80?transport=tcp',
					'credential': 'homeo',
					'username': 'homeo',
				}]
			}
			*/

			'RTCPeerConnectionConfig': null,
			'isInitiator': false,
		});
	},

	didInsertElement()
	{
		this._super(...arguments);
		this.set('localVideoElement', document.getElementById('local-video'));
		this.set('remoteVideoElement', document.getElementById('remote-video'));
	}
});
