# PlexVR for JanusVR

PlexVR is a php-based project for watching your [Plex][2] Movies in VR using [JanusVR][1].
Basically it writes code for a JanusVR compatible "room", using the url commands from Plex.

See [demo][4]
###### Requirements:
  - [Oculus Rift][3]
  - [JanusVR][1]
  - local php webserver

###### * This project is a WIP and ideas/code are very welcome. 
Currently only 60 movies/thumbnails are rendered when entering the room.
Nothing dynamic.

###### Version
0.1

#### How to use:
Clone the repository somewhere on your webserver.

Edit **index.php** and modify the Plex server IP:
```sh
$PLEX_URL="http://<plex server IP>:32400";
```

Open Janus and create a new portal, open the PlexVR webserver url and enter the room.
Use Fly mode and fly/click on any thumbnail, enter the portal/room and click on the movie-screen to play.


[1]:http://janusvr.com
[2]:http://plex.tv
[3]:http://oculus.com
[4]:https://www.youtube.com/watch?v=H1vIoBBp4YE

