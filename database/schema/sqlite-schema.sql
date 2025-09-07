CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "departments"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "code" varchar not null,
  "location" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "departments_code_unique" on "departments"("code");
CREATE TABLE IF NOT EXISTS "operating_rooms"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "code" varchar not null,
  "location" varchar not null,
  "description" text,
  "department_id" integer not null,
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("department_id") references "departments"("id") on delete cascade
);
CREATE UNIQUE INDEX "operating_rooms_code_unique" on "operating_rooms"("code");
CREATE TABLE IF NOT EXISTS "instrument_categories"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "sort_order" integer not null default '0',
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "instrument_statuses"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "color" varchar not null default '#6B7280',
  "description" text,
  "sort_order" integer not null default '0',
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "container_types"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "sort_order" integer not null default '0',
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "container_statuses"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "color" varchar not null default '#6B7280',
  "description" text,
  "sort_order" integer not null default '0',
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "defect_types"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "severity" varchar check("severity" in('niedrig', 'mittel', 'hoch', 'kritisch')) not null default 'mittel',
  "description" text,
  "sort_order" integer not null default '0',
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "purchase_order_statuses"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "color" varchar not null default '#6B7280',
  "description" text,
  "sort_order" integer not null default '0',
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "manufacturers"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "website" varchar,
  "contact_person" varchar,
  "contact_email" varchar,
  "contact_phone" varchar,
  "description" text,
  "is_active" tinyint(1) not null default '1',
  "sort_order" integer not null default '0',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "suppliers"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "contact_person" varchar,
  "contact_email" varchar,
  "contact_phone" varchar,
  "address" text,
  "website" varchar,
  "description" text,
  "is_active" tinyint(1) not null default '1',
  "sort_order" integer not null default '0',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "containers"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "barcode" varchar,
  "description" text,
  "capacity" integer,
  "type_id" integer,
  "status_id" integer,
  "current_location_id" integer,
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("type_id") references "container_types"("id") on delete set null,
  foreign key("status_id") references "container_statuses"("id") on delete set null,
  foreign key("current_location_id") references "departments"("id") on delete set null
);
CREATE UNIQUE INDEX "containers_barcode_unique" on "containers"("barcode");
CREATE TABLE IF NOT EXISTS "instruments"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "serial_number" varchar not null,
  "manufacturer_id" integer,
  "model" varchar,
  "category_id" integer,
  "purchase_price" numeric,
  "purchase_date" date,
  "warranty_until" date,
  "description" text,
  "status_id" integer,
  "current_container_id" integer,
  "current_location_id" integer,
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("manufacturer_id") references "manufacturers"("id") on delete set null,
  foreign key("category_id") references "instrument_categories"("id") on delete set null,
  foreign key("status_id") references "instrument_statuses"("id") on delete set null,
  foreign key("current_container_id") references "containers"("id") on delete set null,
  foreign key("current_location_id") references "departments"("id") on delete set null
);
CREATE UNIQUE INDEX "instruments_serial_number_unique" on "instruments"(
  "serial_number"
);
CREATE TABLE IF NOT EXISTS "defect_reports"(
  "id" integer primary key autoincrement not null,
  "instrument_id" integer not null,
  "defect_type_id" integer,
  "reported_by" integer not null,
  "reporting_department_id" integer,
  "description" text not null,
  "severity" varchar check("severity" in('niedrig', 'mittel', 'hoch', 'kritisch')) not null default 'mittel',
  "status" varchar check("status" in('offen', 'in_bearbeitung', 'abgeschlossen', 'abgelehnt')) not null default 'offen',
  "reported_at" datetime not null default CURRENT_TIMESTAMP,
  "assigned_to" integer,
  "resolved_at" datetime,
  "resolution_notes" text,
  "repair_cost" numeric,
  "created_at" datetime,
  "updated_at" datetime,
  "photos" text,
  foreign key("instrument_id") references "instruments"("id") on delete cascade,
  foreign key("defect_type_id") references "defect_types"("id") on delete set null,
  foreign key("reported_by") references "users"("id") on delete cascade,
  foreign key("reporting_department_id") references "departments"("id") on delete set null,
  foreign key("assigned_to") references "users"("id") on delete set null
);
CREATE TABLE IF NOT EXISTS "purchase_orders"(
  "id" integer primary key autoincrement not null,
  "order_number" varchar not null,
  "supplier_id" integer,
  "manufacturer_id" integer,
  "defect_report_id" integer,
  "status_id" integer,
  "ordered_by" integer not null,
  "approved_by" integer,
  "received_by" integer,
  "order_date" date not null,
  "expected_delivery" date,
  "delivery_date" date,
  "received_at" datetime,
  "total_amount" numeric,
  "notes" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("supplier_id") references "suppliers"("id") on delete set null,
  foreign key("manufacturer_id") references "manufacturers"("id") on delete set null,
  foreign key("defect_report_id") references "defect_reports"("id") on delete set null,
  foreign key("status_id") references "purchase_order_statuses"("id") on delete set null,
  foreign key("ordered_by") references "users"("id") on delete cascade,
  foreign key("approved_by") references "users"("id") on delete set null,
  foreign key("received_by") references "users"("id") on delete set null
);
CREATE UNIQUE INDEX "purchase_orders_order_number_unique" on "purchase_orders"(
  "order_number"
);
CREATE TABLE IF NOT EXISTS "instrument_movements"(
  "id" integer primary key autoincrement not null,
  "instrument_id" integer not null,
  "movement_type" varchar check("movement_type" in('location_change', 'container_assignment', 'container_removal', 'status_change', 'maintenance')) not null,
  "from_location" varchar,
  "to_location" varchar,
  "from_container_id" integer,
  "to_container_id" integer,
  "from_status" varchar,
  "to_status" varchar,
  "performed_by" integer not null,
  "notes" text,
  "performed_at" datetime not null default CURRENT_TIMESTAMP,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("instrument_id") references "instruments"("id") on delete cascade,
  foreign key("from_container_id") references "containers"("id") on delete set null,
  foreign key("to_container_id") references "containers"("id") on delete set null,
  foreign key("performed_by") references "users"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "role" varchar not null default('viewer'),
  "department_id" integer,
  "is_active" tinyint(1) not null default('1'),
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("department_id") references "departments"("id") on delete set null
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE INDEX "instruments_is_active_status_id_index" on "instruments"(
  "is_active",
  "status_id"
);
CREATE INDEX "instruments_current_container_id_index" on "instruments"(
  "current_container_id"
);
CREATE INDEX "instruments_current_location_id_index" on "instruments"(
  "current_location_id"
);
CREATE INDEX "instruments_category_id_is_active_index" on "instruments"(
  "category_id",
  "is_active"
);
CREATE INDEX "containers_is_active_status_id_index" on "containers"(
  "is_active",
  "status_id"
);
CREATE INDEX "containers_type_id_is_active_index" on "containers"(
  "type_id",
  "is_active"
);
CREATE INDEX "containers_current_location_id_index" on "containers"(
  "current_location_id"
);
CREATE INDEX "defect_reports_status_severity_index" on "defect_reports"(
  "status",
  "severity"
);
CREATE INDEX "defect_reports_reported_at_index" on "defect_reports"(
  "reported_at"
);
CREATE INDEX "defect_reports_instrument_id_status_index" on "defect_reports"(
  "instrument_id",
  "status"
);
CREATE INDEX "purchase_orders_status_id_order_date_index" on "purchase_orders"(
  "status_id",
  "order_date"
);
CREATE INDEX "purchase_orders_supplier_id_index" on "purchase_orders"(
  "supplier_id"
);
CREATE INDEX "purchase_orders_manufacturer_id_index" on "purchase_orders"(
  "manufacturer_id"
);
CREATE INDEX "instrument_movements_instrument_id_performed_at_index" on "instrument_movements"(
  "instrument_id",
  "performed_at"
);
CREATE INDEX "instrument_movements_movement_type_performed_at_index" on "instrument_movements"(
  "movement_type",
  "performed_at"
);
CREATE UNIQUE INDEX "instrument_categories_name_unique" on "instrument_categories"(
  "name"
);
CREATE UNIQUE INDEX "instrument_statuses_name_unique" on "instrument_statuses"(
  "name"
);
CREATE UNIQUE INDEX "container_types_name_unique" on "container_types"("name");
CREATE UNIQUE INDEX "container_statuses_name_unique" on "container_statuses"(
  "name"
);
CREATE UNIQUE INDEX "defect_types_name_unique" on "defect_types"("name");
CREATE UNIQUE INDEX "purchase_order_statuses_name_unique" on "purchase_order_statuses"(
  "name"
);
CREATE UNIQUE INDEX "manufacturers_name_unique" on "manufacturers"("name");
CREATE UNIQUE INDEX "suppliers_name_unique" on "suppliers"("name");

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2025_08_06_051457_create_configuration_tables',1);
INSERT INTO migrations VALUES(5,'2025_08_06_051506_create_business_tables',1);
INSERT INTO migrations VALUES(6,'2025_08_06_051515_add_foreign_keys_and_relationships',1);
INSERT INTO migrations VALUES(7,'2025_08_06_201211_add_photos_to_defect_reports_table',2);
