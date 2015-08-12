<?php
class SiteDB
{
	const DB_CONFIG_FILE = DIR_BASE.'db_config.ini';
	public $db_host;
	public $db_port;
	public $db_user;
	public $db_pass;
	public $db_name;
	public $sql_conn;
	public $error;

	function __construct()
	{
		// Load configuration
		if (!file_exists(self::DB_CONFIG_FILE))
			trigger_error("SiteDB::__construct(): File db_config.ini doesn't exists.", E_USER_ERROR);

		if (!($fp = fopen(self::DB_CONFIG_FILE, 'r')))
			trigger_error("SiteDB::__construct(): Unable to open db_config.ini for reading.", E_USER_ERROR);

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
					default:
						echo "db_config.ini: $line_num: Unknown option $option";
						break;
				}
			}
			else
				echo  "db_config.ini: $line_num: Invalid option format";
		}
		if (!feof($fp))
			trigger_error("SiteDB::__construct(): Reading file db_config.ini has ended but no EOF was found.", E_USER_ERROR);

		fclose($fp);

		// Create connection
		if (!isset($this->sql_conn))
			$this->sql_conn = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name, $this->db_port);

		// Check connection
		if ($this->sql_conn->connect_error)
			trigger_error('SiteDB::__construct(): '.$this->sql_conn->connect_error, E_USER_ERROR);
	}
	function __destruct()
	{
		if (isset($this->sql_conn))
			$this->sql_conn->close();
	}
	function sanitize_input($input_str)
	{
		// This function removes any malicious input a user might have used to compromise the system
		$input_str = stripslashes(htmlentities(strip_tags($input_str)));

		return $this->sql_conn->real_escape_string($input_str);
	}
	function save_db_config($db_data)
	{
		$this->db_host = isset($db_data['dbHost']) ? $db_data['dbHost'] : "";
		$this->db_port = isset($db_data['dbPort']) ? $db_data['dbPort'] : 0;
		$this->db_user = isset($db_data['dbUser']) ? $db_data['dbUser'] : "";
		$this->db_pass = isset($db_data['dbPass']) ? $db_data['dbPass'] : "";
		$this->db_name = isset($db_data['dbName']) ? $db_data['dbName'] : "";

		if (!($fp = fopen(self::DB_CONFIG_FILE, 'w')))
		{
			$this->error = "SiteDB::save_db_config(): Unable to open db_config.ini for writting.";
			return false;
		}
		
		fprintf($fp, "DatabaseHostname = %s\nDatabasePort = %d\nDatabaseUsername = %s\nDatabasePassword = %s\nDatabaseName = %s\n",
			$this->db_host, $this->db_port, $this->db_user, $this->db_pass, $this->db_name);

		fclose($fp);
		
		return true;
	}
}
?>