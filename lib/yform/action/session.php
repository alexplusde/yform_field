class rex_yform_action_saveinsession extends rex_yform_action_abstract
{
    public function executeAction() :void
    {
			foreach(
				$this->params['value_pool']['sql'] as $key => $value) {
					$_SESSION['yform']['session'][$key] = htmlspecialchars($value);
			}
		}

    public function getDescription() :string
    {
        return "action|saveinsession <b>[Session Plugin]</b> --> Schreibt Feldwerte in $SESSION";
    }
}
