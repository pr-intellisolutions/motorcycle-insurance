<?php
class SiteConfig
{
	public $site_name;
	public $site_desc;
	public $site_host;
	public $site_module;

	public $db_host;
	public $db_port;
	public $db_user;
	public $db_pass;
	public $db_name;

	public $user_minlen;
	public $user_maxlen;
	public $user_complexity;

	public $pass_minlen;
	public $pass_maxlen;
	public $pass_complexity;
	public $pass_expiration;

	public $max_login_attempts;

	function __construct()
	{
		$config_file = CONFIG_FILE;

		if (!file_exists($config_file))
			trigger_error("SiteConfig::__construct(): File $config_file doesn't exists.", E_USER_ERROR);

		if (!($fp = fopen($config_file, 'r')))
			trigger_error("SiteConfig::__construct(): Unable to open $config_file for reading.", E_USER_ERROR);

		$line_num = 0;
		while (($line = fgets($fp)) != false)
		{
			$option = "";
			$value = "";
			$line_num++;

			// If a line contains only white spaces, ignore that line
			if (ctype_space($line))
				continue;

			// Trim white spaces at the start and at the end of the string
			$line = trim($line);

			// Comments start with a # sign. If the first character of the line is a #, ignore that line.
			if ($line[0] == '#')
				continue;

			// The format for the options is of the form option = value.
			$i = sscanf($line, "%[^ =\n]%*[ =]%[^\n]", $option, $value);

			// All checked good now load the corresponding options into memory.
			// i = 2 means no value was given for the option and a default value must be given
			if ($i == 2 || $i == 3)
			{
				switch ($option)
				{
					// For explanations of all the options consult the config.ini configuration file
					case "SiteName":
						$this->site_name = ($i == 2) ? "" : $value;
						break;
					case "SiteDescription":
						$this->site_desc = ($i == 2) ? "" : $value;
						break;
					case "SiteHostname":
						$this->site_host = ($i == 2) ? "" : $value;
						break;
					case "SiteModule":
						$this->site_module = ($i == 2) ? "" : $value;
						break;
					case "DatabaseHostname":
						$this->db_host =($i == 2) ? "" : $value;
						break;
					case "DatabasePort":
						$this->db_port = ($i == 2) ? 0 : $value;
						break;
					case "DatabaseUsername":
						$this->db_user = ($i == 2) ? "" : $value;
						break;
					case "DatabasePassword":
						$this->db_pass = ($i == 2) ? "" : $value;
						break;
					case "DatabaseName":
						$this->db_name = ($i == 2) ? "" : $value;
						break;
					case "UsernameMinimumLength":
						$this->user_minlen = ($i == 2) ? 0 : $value;
						break;
					case "UsernameMaximumLength":
						$this->user_maxlen = ($i == 2) ? 0 : $value;
						break;
					case "UsernameComplexity":
						$this->user_complexity = ($i == 2) ? "" : $value;
						break;
					case "PasswordMinimumLength":
						$this->pass_minlen = ($i == 2) ? 0 : $value;
						break;
					case "PasswordMaximumLength":
						$this->pass_maxlen = ($i == 2) ? 0 : $value;
						break;
					case "PasswordComplexity":
						$this->pass_complexity = ($i == 2) ? "" : $value;
						break;
					case "PasswordExpiration":
						$this->pass_expiration = ($i == 2) ? 0 : $value;
						break;
					case "MaximumLoginAttempts":
						$this->max_login_attempts = ($i == 2) ? 0 : $value;;
						break;
					default:
						echo "$config_file: $line_num: Unknown option $option";
						break;
				}
			}
			else
				echo  "$config_file: $line_num: Invalid option format";
		}
		if (!feof($fp))
			trigger_error("SiteConfig::__construct(): Reading file $config_file has ended but no EOF was found.", E_USER_ERROR);

		fclose($fp);
	}
}
?>
