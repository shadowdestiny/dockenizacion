	
var done=0;
var i=0;
var list = [];


var cluster = require('cluster');
var numCPUs = require('os').cpus().length;
var numCPUs = 1;

var curWorkers = 0;
var workerStatus = [];
var workers = [];

if (cluster.isMaster) 
{
	/*
	log('Starting Application....');
	sleep(2000);
	log( "Opening Threads..");
	*/
	
	var mysql      = require('mysql');
	var connection = mysql.createConnection({
	  host     : '192.168.77.166',
	  user     : "root",
	  password : 'tpl7'
	});

	connection.connect();
	


//	connection.end();	
	
	for(var k=0;k<30;k++)
	{
		//list.push(k);
	}
	
	
	// fill queue with data
	setInterval(function()
    {
        if(list.length<=10)
        {                
			
			var sql = "select * from euromillions.mail_queue where status='new' order by priority,id limit 500";
			connection.query(sql, function(err, rows, fields) 
			{						
				if (err) throw err;
				for (var index in rows)
				{
					var d = dateFormat(new Date() ,"%Y-%m-%d %H:%H:%S" );
					var sql = "update euromillions.mail_queue set status='starting_nodejs',last_try='" + d +"' where id=" + rows[index].id;
					list.push(rows[index].id);
//					console.log("AKTUELLE LISTE: " + list.length );
				}
			});
        }		
    },5000,"fill");
	
	
	
	
	for(var i=1;i <= numCPUs;i++)
	{
		cluster.fork();
		curWorkers++;
		workerStatus[ i ] = 'opening';
	}
/*
	cluster.on('listening', function (worker) 
	{
		console.log("d2");

		log('Worker ' + worker.id + ' ready and listening');
		workerStatus [ worker.id ] ='ready';

		setTimeout(function () 
		{
			worker.send('Hello Worker');
		}, 2000);
	}
	);
*/
	for (var i in cluster.workers) 
	{		
		cluster.workers[i].on('message', function (i, msg) 
		{
//			log('Worker ' + i + ' said: ' + msg.cmd);
			
			if (msg.cmd)
            {
//                log("cmd from " + i);
                switch (msg.cmd)
                {
					case 'opened':
//						log("Worker " + i + " is now ready to start");
						workerStatus[ i ]='ready';
						break;
					case 'getNextTask':
						//if( list.length>0)
						if( workerStatus[ i ] =='ready' && list.length>0)
						{
							workerStatus[ i ]='starting';
							var id = list[0];
							list.shift();
							
							var d = dateFormat(new Date() ,"%Y-%m-%d %H:%H:%S" );
							
							var sql = "update euromillions.mail_queue set status='starting_nodejs',last_try='" + d +"' where id=" + id;
							connection.query(sql, function(err, rows, fields) 
							{								
								cluster.workers[i].send({ cmd: 'startTask', id: id });
//                        log("send new task to worker " + j + ": " + id);
							});
							//process.exit();
						}
						break;
                  case 'done':
                    //broadcast();
                    
					var d = dateFormat(new Date() ,"%Y-%m-%d %H:%H:%S" );
						
					var sql = "update euromillions.mail_queue set status='done',last_try='" + d +"' where id=" + msg.id;
//					var sql = "delete from euromillions.mail_queue where id=" + msg.id;
					connection.query(sql, function(err, rows, fields) 
					{
						workerStatus[ i ]='ready';
						done++;
					});
					break;
				case 'seterror':
					var d = dateFormat(new Date() ,"%Y-%m-%d %H:%H:%S" );						
					var sql = "update euromillions.mail_queue set status='error',last_try='" + d +"' where id=" + msg.id;
//					connection.query(sql, function(err, rows, fields) 
//					{
						workerStatus[ i ]='ready';
						error++;
//					});
					break;
                }
            }			
		}.bind(this, i));
		
	}

} else {
	
	var render = require('php-node')({bin:"/usr/bin/php"});
	var state = 'starting';
	
	//sleep(5000);
	
	process.send ( { cmd: "opened"} );
	state = 'ready';
	
	cluster.worker.on('message', function (msg) 
	{
		if (msg.cmd)
        {
            switch (msg.cmd)
            {
                case 'startTask':
					log("worker get new task: " + msg.id);
					
					state='in_progress';
					
					render(__dirname+'/../../cron.php -m default -c cron_mailqueue -a processitem -i ' + msg.id, {}, function(e, r)  {
						//log(r);
						//var r="OK";
						
						if(r == "OK")
						{
							log("Worker is done for id: " + msg.id);
							process.send({ cmd: 'done' , id: msg.id });
						} else {
							log("Error by processing php for id: " + msg.id);
							process.send({ cmd: 'seterror' , id: msg.id });
						}
						state='ready';
					 })										
					//sleep(1000);					
                    break;
				case 'exit':
					process.send( { cmd: "quit" } );
					process.exit();
					break;
            }
        }
//		log("Worker get cmd from master: " + msg.id);
	});

	setInterval( function()
	{
		if(state=='ready')
		{
			log("Worker ask for new Task..");
			process.send({ cmd: "getNextTask" });			
		}
	},rnd(100,110));
}





var os = require('os');
var Monitor = function () {
this.totalmem = os.totalmem() / 1048576 + ' MB';
this.uptime;
this.freemem;
this.load;
}
Monitor.prototype.setUptime = function()
{
    var seconds = os.uptime();
    var hours = Math.floor(seconds / 3600),
    minutes = Math.floor((seconds - hours * 3600) / 60),
    seconds = (seconds - hours * 3600 - minutes * 60);
    this.uptime = hours + ':' + minutes + ':' + seconds;
}
Monitor.prototype.setFreemem = function()
{
    this.freemem = this.formatNumber(os.freemem() / 1048576);
}
Monitor.prototype.setLoad = function ()
{
    this.load = this.formatNumber(os.loadavg()[0]);
}
Monitor.prototype.formatNumber = function(number)
{
    return Math.round(number * 100) / 100;
}
Monitor.prototype.clearScreen = function ()
{
    for(var i = 0; i < process.stdout.rows; i++)
    {
        console.log('\r\n');
    }
}

Monitor.prototype.update = function ()
{
    this.setUptime();
    this.setFreemem();
    this.setLoad();
    return this;
}
Monitor.prototype.output = function()
{
    this.clearScreen();

    for (var i in workerStatus)
    {
        console.log("Status: " + workerStatus[i] );
    }
    //console.log(this.hostname);
    //console.log(this.sysInfo);
    console.log("Aktuell in der Queue: " + list.length);
    console.log("Aktueller Durchlauf: " + i);
    console.log("Done: " + done);
    console.log('Uptime: ' + this.uptime);
    var free = this.freemem;
    var total = this.totalmem;
    console.log('Freemem: ' + free + ' MB of ' + total + ' MB');
    console.log('Load: ' + this.load);
}

if (cluster.isMaster)
{
    var sysMon = new Monitor();
    setInterval(function() {sysMon.update().output()}, 250);
}


function rnd(Min,Max) 
{ 
	var Zufall,Zufallszahl;
	Zufall = Math.random();
	Zufallszahl = Min + parseInt(Zufall * ( (Max+1) - Min ));
	return Zufallszahl;
}

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}


function dateFormat (date, fstr, utc) {
  utc = utc ? 'getUTC' : 'get';
  return fstr.replace (/%[YmdHMS]/g, function (m) {
    switch (m) {
    case '%Y': return date[utc + 'FullYear'] (); // no leading zeros required
    case '%m': m = 1 + date[utc + 'Month'] (); break;
    case '%d': m = date[utc + 'Date'] (); break;
    case '%H': m = date[utc + 'Hours'] (); break;
    case '%M': m = date[utc + 'Minutes'] (); break;
    case '%S': m = date[utc + 'Seconds'] (); break;
    default: return m.slice (1); // unknown code, remove %
    }
    // add leading zero if required
    return ('0' + m).slice (-2);
  });
}

function log(msg)
{
	//console.log( msg );
}