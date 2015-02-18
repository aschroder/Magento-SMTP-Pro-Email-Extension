--
-- Suppress all emails in SMTPPro extension
--
update `core_config_data` set `value`=1 where `path`='smtppro/debug/logenabled';
update `core_config_data` set `value`=1 where `path`='smtppro/debug/cleanlog';
update `core_config_data` set `value`=1 where `path`='smtppro/debug/log_debug';


--
-- Cleanup logs
--
TRUNCATE TABLE smtppro_email_log;
