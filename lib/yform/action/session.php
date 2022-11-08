class rex_yform_action_tosession extends rex_yform_action_abstract
{
	public function executeAction() :void
	{
		/* TODO: Session starten, falls noch nicht geschehen? */
		foreach($this->params['value_pool']['sql'] as $key => $value) 
		{
			/* TODO: rex_set_session('meine_var', $meine_var) - Tabellen-Key berücksichtigen, um Action mehrfach einsetzen zu können. */
			$_SESSION['yform']['session'][$key] = htmlspecialchars($value);
		}
	}

	public function getDescription() :string
	{
		return "action|tosession <b>Schreibt alle Feldwerte in $SESSION";
	}
	/* TODO: Statische Methode zum Auslesen der $SESSION bereitstellen. */
}

	
