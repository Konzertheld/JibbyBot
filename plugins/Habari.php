<?php

	class Phergie_Plugin_Habari extends Phergie_Plugin_Abstract
	{
		public function onDoWa($question)
		{
			$url = sprintf('http://tumbolia.appspot.com/wa/%s', urlencode($question));
			$this->doPrivmsg($this->event->getSource(), file_get_contents($url));
		}
		
		public function onCommandGuid ( ) {
			
			$guid = array();
			for ( $i = 0; $i < 16; $i++ ) {
				$guid[] = mt_rand( 0, 255 );
			}
			
			$guid[8] = ( $guid[8] & 0x3f ) | 0x80;
			$guid[6] = ( $guid[6] & 0x0f ) | 0x40;
			
			// convert to hex
			$hex = '';
			
			for ( $i = 0; $i < 16; $i++ ) {
				if ( $i == 4 || $i == 6 || $i == 8 || $i == 10 ) {
					$hex .= '-';
				}
				$hex .= sprintf( '%02x', $guid[ $i ] );
			}
			
			
			$this->doPrivmsg( $this->getEvent()->getSource(), $this->getEvent()->getNick() . ': ' . strtoupper($hex) );
		}
		
		public function onCommandUuid ( ) {
			$this->onCommandGuid();
		}
		
		protected function wikiSearch($search) {
			$source = $this->event->getSource();
			$nick = $this->event->getNick();
			$dat = file_get_contents('http://wiki.habariproject.org/w/index.php?title=Special:Search&fulltext=Search&search=' . urlencode($search));
			preg_match('/<li><a href="(.*?)"/', $dat, $m);
			$link = $m[1];
			$msg =  "{$nick} wiki search for '{$search}': http://wiki.habariproject.org{$link}";
			$this->doPrivmsg($source, $msg);
		}
		
		public function onCommandWiki($search)
		{
			return $this->wikiSearch($search);
		}
		
		public function onCommandRev ( $extras = '' ) {
			$this->doPrivmsg($this->getEvent()->getSource(), "Functionality currently broken"); return;
			
			if ( $extras == 'extras' ) {
				$url = 'habari-extras';
				$name = 'Extras';
			}
			else {
				$url = 'habari';
				$name = 'Core';
			}
			
			$url = 'http://svn.habariproject.org/' . $url;
			
			$info = shell_exec( 'svn info ' . $url );
			
			preg_match( '/Revision: (\d+)/i', $info, $m );
			
			$this->doPrivmsg( $this->getEvent()->getSource(), $this->getEvent()->getNick() . ': Current ' . $name . ' Revision: ' . $m[1] );
			
		}
		
		public function onCommandTranslation ( $language )
		{
			$this->doPrivmsg($this->getEvent()->getSource(), "Functionality currently broken"); return;
			
			$chan = $this->getEvent()->getSource();
			$nick = $this->getEvent()->getNick();
			
			$data = file_get_contents( 'https://translations.launchpad.net/habari/trunk/+pots/habari' . urlencode( $language ) . '/+index' );
			
			preg_match( '#To do:</b>.*?(\d+)#s', $data, $m );
			
			if ( !isset( $m[1] ) ) {
				$msg = "$nick, Could not find language '$language'";
			}
			else {
				$todo = $m[1];
				$msg = "$nick, $language still needs $todo strings translated. Go help https://translations.launchpad.net/habari/trunk/+pots/habari/$language/+translate";
			}
			
			$this->doPrivmsg( $chan, $msg );
			
		}
		
	}

?>