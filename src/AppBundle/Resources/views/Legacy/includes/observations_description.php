<?php
/**
 *   Include File
 *   Observations Description Observations Menu 
 *   VERSION:1.0.0
 *   AUTHOR:De Winter Johan
 *   LAST MODIFIED USER:De Winter Johan
 *   LAST MODIFIED DATE:25/01/2010 
 */
ob_start();
?>

<h3> Marine mammals in Belgium</h3>

<p>In contrast to common belief, the southern North Sea is home to a number of marine mammals, such as harbour porpoises, white-beaked dolphins, and common and grey seals. Also species occurring in the adjacent Atlantic Ocean and the northern North Sea may frequent our waters, or wander into them. As such in historical times strandings and/or observations occurred at the Belgian coast of 19 cetacean and 5 pinniped species.</p>

<p>The Royal Belgian Institute of Natural Sciences (RBINS) is famous for its museum, and the extensive collection of whale skeletons. Its department Management Unit of the North Sea Mathematical Models (MUMM), with scientists working in Brussels and Ostend, collects all stranding and sighting records in a database.  This database contains information form many different sources, including data supplied by the public, data collected by the RBINS itself, and data recorded in literature and historic documents. Part of the database can be consulted online: all strandings since 2000, and the most remarkable sightings</p>

<p>The RBINS (MUMM) coordinates an intervention network dealing with the scientific research of stranded and bycaught marine mammals. This research focuses on the problems encountered by these animals, such as pollution and overfishing, but can also put more light on ecology and life history aspects of the populations. Next to this, MUMM investigates effects on marine mammals of human activities at sea, such as the construction of offshore windfarms.</p>

<p>RBINS (MUMM) is the competent authority in the national legislation specifically protecting certain marine mammal species (Royal Decree of 21 December 2001). Protecting these magnificent creatures is a commitment, and even an obligation of the Belgian authorities in the framework of different international conventions and treaties, such as the European Habitats Directive, ASCOBANS and the OSPAR Convention.  As protected animals, marine mammals cannot be disturbed on purpose, and may not be caught or killed. Stranded or bycaught animals need to be notified to the authorities.</p>

<p>In case you observe a seal or a dolphin, or a group of them, you can notify this to the RBINS (MUMM) 
by email to <a href="mailto:dolphin@mumm.ac.be">dolphin@mumm.ac.be</a>. In case of strandings, you can contact a local authority (police or fire brigade). Live stranded seals - in difficulty - are being dealt with by SeaLife Blankenberge.  For transporting live stranded cetaceans (porpoises and small dolphins), RBINS (MUMM) has special equipment standby at Ostend.</p>  

<?php
$tmp = ob_get_contents();
ob_end_clean();
return $tmp;
?>