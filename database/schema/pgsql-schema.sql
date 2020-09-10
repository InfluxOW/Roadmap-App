--
-- PostgreSQL database dump
--

-- Dumped from database version 11.7 (Ubuntu 11.7-2.pgdg19.10+1)
-- Dumped by pg_dump version 12.4 (Ubuntu 12.4-0ubuntu0.20.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

--
-- Name: companies; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.companies (
    id bigint NOT NULL,
    website character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    description text NOT NULL,
    foundation_year smallint NOT NULL,
    industry character varying(255) NOT NULL,
    location character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: companies_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.companies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: companies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.companies_id_seq OWNED BY public.companies.id;


--
-- Name: course_technologies; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.course_technologies (
    technology_id bigint NOT NULL,
    course_id bigint NOT NULL
);


--
-- Name: courses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.courses (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    description text NOT NULL,
    source character varying(255) NOT NULL,
    employee_level_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: courses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.courses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: courses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.courses_id_seq OWNED BY public.courses.id;


--
-- Name: development_directions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.development_directions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL
);


--
-- Name: development_directions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.development_directions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: development_directions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.development_directions_id_seq OWNED BY public.development_directions.id;


--
-- Name: employee_course_completions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.employee_course_completions (
    employee_id bigint NOT NULL,
    course_id bigint NOT NULL,
    rating character varying(255) NOT NULL,
    completed_at timestamp(0) without time zone NOT NULL,
    CONSTRAINT employee_course_completions_rating_check CHECK (((rating)::text = ANY ((ARRAY['1'::character varying, '2'::character varying, '3'::character varying, '4'::character varying, '5'::character varying, '6'::character varying, '7'::character varying, '8'::character varying, '9'::character varying, '10'::character varying])::text[])))
);


--
-- Name: employee_development_directions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.employee_development_directions (
    employee_id bigint NOT NULL,
    development_direction_id bigint NOT NULL
);


--
-- Name: employee_levels; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.employee_levels (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL
);


--
-- Name: employee_levels_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.employee_levels_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: employee_levels_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.employee_levels_id_seq OWNED BY public.employee_levels.id;


--
-- Name: employee_roadmaps; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.employee_roadmaps (
    employee_id bigint NOT NULL,
    preset_id bigint NOT NULL,
    manager_id bigint NOT NULL,
    assigned_at timestamp(0) without time zone NOT NULL
);


--
-- Name: employee_technologies; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.employee_technologies (
    employee_id bigint NOT NULL,
    technology_id bigint NOT NULL
);


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: oauth_access_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.oauth_access_tokens (
    id character varying(100) NOT NULL,
    user_id bigint,
    client_id bigint NOT NULL,
    name character varying(255),
    scopes text,
    revoked boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone
);


--
-- Name: oauth_auth_codes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.oauth_auth_codes (
    id character varying(100) NOT NULL,
    user_id bigint NOT NULL,
    client_id bigint NOT NULL,
    scopes text,
    revoked boolean NOT NULL,
    expires_at timestamp(0) without time zone
);


--
-- Name: oauth_clients; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.oauth_clients (
    id bigint NOT NULL,
    user_id bigint,
    name character varying(255) NOT NULL,
    secret character varying(100),
    provider character varying(255),
    redirect text NOT NULL,
    personal_access_client boolean NOT NULL,
    password_client boolean NOT NULL,
    revoked boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: oauth_clients_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.oauth_clients_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: oauth_clients_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.oauth_clients_id_seq OWNED BY public.oauth_clients.id;


--
-- Name: oauth_personal_access_clients; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.oauth_personal_access_clients (
    id bigint NOT NULL,
    client_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: oauth_personal_access_clients_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.oauth_personal_access_clients_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: oauth_personal_access_clients_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.oauth_personal_access_clients_id_seq OWNED BY public.oauth_personal_access_clients.id;


--
-- Name: oauth_refresh_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.oauth_refresh_tokens (
    id character varying(100) NOT NULL,
    access_token_id character varying(100) NOT NULL,
    revoked boolean NOT NULL,
    expires_at timestamp(0) without time zone
);


--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: preset_courses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.preset_courses (
    preset_id bigint NOT NULL,
    course_id bigint NOT NULL,
    assigned_at timestamp(0) without time zone NOT NULL
);


--
-- Name: presets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.presets (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    description text,
    manager_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: presets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.presets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: presets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.presets_id_seq OWNED BY public.presets.id;


--
-- Name: team_members; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.team_members (
    user_id bigint NOT NULL,
    team_id bigint NOT NULL,
    assigned_at timestamp(0) without time zone NOT NULL
);


--
-- Name: teams; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.teams (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    owner_id bigint NOT NULL,
    company_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: teams_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.teams_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: teams_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.teams_id_seq OWNED BY public.teams.id;


--
-- Name: technologies; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.technologies (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    description text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: technologies_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.technologies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: technologies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.technologies_id_seq OWNED BY public.technologies.id;


--
-- Name: technology_development_directions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.technology_development_directions (
    technology_id bigint NOT NULL,
    development_direction_id bigint NOT NULL
);


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    username character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    role character varying(255) NOT NULL,
    company_id bigint,
    sex character varying(255),
    birthday timestamp(0) without time zone,
    "position" character varying(255),
    remember_token character varying(100),
    email_verified_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT users_sex_check CHECK (((sex)::text = ANY ((ARRAY['male'::character varying, 'female'::character varying])::text[])))
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: companies id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.companies ALTER COLUMN id SET DEFAULT nextval('public.companies_id_seq'::regclass);


--
-- Name: courses id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses ALTER COLUMN id SET DEFAULT nextval('public.courses_id_seq'::regclass);


--
-- Name: development_directions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.development_directions ALTER COLUMN id SET DEFAULT nextval('public.development_directions_id_seq'::regclass);


--
-- Name: employee_levels id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_levels ALTER COLUMN id SET DEFAULT nextval('public.employee_levels_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: oauth_clients id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_clients ALTER COLUMN id SET DEFAULT nextval('public.oauth_clients_id_seq'::regclass);


--
-- Name: oauth_personal_access_clients id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_personal_access_clients ALTER COLUMN id SET DEFAULT nextval('public.oauth_personal_access_clients_id_seq'::regclass);


--
-- Name: presets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.presets ALTER COLUMN id SET DEFAULT nextval('public.presets_id_seq'::regclass);


--
-- Name: teams id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teams ALTER COLUMN id SET DEFAULT nextval('public.teams_id_seq'::regclass);


--
-- Name: technologies id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.technologies ALTER COLUMN id SET DEFAULT nextval('public.technologies_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: companies companies_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.companies
    ADD CONSTRAINT companies_name_unique UNIQUE (name);


--
-- Name: companies companies_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.companies
    ADD CONSTRAINT companies_pkey PRIMARY KEY (id);


--
-- Name: companies companies_website_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.companies
    ADD CONSTRAINT companies_website_unique UNIQUE (website);


--
-- Name: course_technologies course_technologies_technology_id_course_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_technologies
    ADD CONSTRAINT course_technologies_technology_id_course_id_unique UNIQUE (technology_id, course_id);


--
-- Name: courses courses_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_name_unique UNIQUE (name);


--
-- Name: courses courses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_pkey PRIMARY KEY (id);


--
-- Name: courses courses_source_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_source_unique UNIQUE (source);


--
-- Name: development_directions development_directions_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.development_directions
    ADD CONSTRAINT development_directions_name_unique UNIQUE (name);


--
-- Name: development_directions development_directions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.development_directions
    ADD CONSTRAINT development_directions_pkey PRIMARY KEY (id);


--
-- Name: employee_course_completions employee_course_completions_employee_id_course_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_course_completions
    ADD CONSTRAINT employee_course_completions_employee_id_course_id_unique UNIQUE (employee_id, course_id);


--
-- Name: employee_development_directions employee_development_directions_employee_id_development_directi; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_development_directions
    ADD CONSTRAINT employee_development_directions_employee_id_development_directi UNIQUE (employee_id, development_direction_id);


--
-- Name: employee_levels employee_levels_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_levels
    ADD CONSTRAINT employee_levels_name_unique UNIQUE (name);


--
-- Name: employee_levels employee_levels_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_levels
    ADD CONSTRAINT employee_levels_pkey PRIMARY KEY (id);


--
-- Name: employee_roadmaps employee_roadmaps_employee_id_preset_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_roadmaps
    ADD CONSTRAINT employee_roadmaps_employee_id_preset_id_unique UNIQUE (employee_id, preset_id);


--
-- Name: employee_technologies employee_technologies_employee_id_technology_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_technologies
    ADD CONSTRAINT employee_technologies_employee_id_technology_id_unique UNIQUE (employee_id, technology_id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: oauth_access_tokens oauth_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_access_tokens
    ADD CONSTRAINT oauth_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: oauth_auth_codes oauth_auth_codes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_auth_codes
    ADD CONSTRAINT oauth_auth_codes_pkey PRIMARY KEY (id);


--
-- Name: oauth_clients oauth_clients_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_clients
    ADD CONSTRAINT oauth_clients_pkey PRIMARY KEY (id);


--
-- Name: oauth_personal_access_clients oauth_personal_access_clients_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_personal_access_clients
    ADD CONSTRAINT oauth_personal_access_clients_pkey PRIMARY KEY (id);


--
-- Name: oauth_refresh_tokens oauth_refresh_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_refresh_tokens
    ADD CONSTRAINT oauth_refresh_tokens_pkey PRIMARY KEY (id);


--
-- Name: preset_courses preset_courses_preset_id_course_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.preset_courses
    ADD CONSTRAINT preset_courses_preset_id_course_id_unique UNIQUE (preset_id, course_id);


--
-- Name: presets presets_name_manager_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.presets
    ADD CONSTRAINT presets_name_manager_id_unique UNIQUE (name, manager_id);


--
-- Name: presets presets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.presets
    ADD CONSTRAINT presets_pkey PRIMARY KEY (id);


--
-- Name: team_members team_members_user_id_team_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.team_members
    ADD CONSTRAINT team_members_user_id_team_id_unique UNIQUE (user_id, team_id);


--
-- Name: teams teams_name_company_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teams
    ADD CONSTRAINT teams_name_company_id_unique UNIQUE (name, company_id);


--
-- Name: teams teams_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teams
    ADD CONSTRAINT teams_pkey PRIMARY KEY (id);


--
-- Name: technologies technologies_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.technologies
    ADD CONSTRAINT technologies_name_unique UNIQUE (name);


--
-- Name: technologies technologies_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.technologies
    ADD CONSTRAINT technologies_pkey PRIMARY KEY (id);


--
-- Name: technology_development_directions technology_development_directions_technology_id_development_dir; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.technology_development_directions
    ADD CONSTRAINT technology_development_directions_technology_id_development_dir UNIQUE (technology_id, development_direction_id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users users_username_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_unique UNIQUE (username);


--
-- Name: course_technologies_course_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX course_technologies_course_id_index ON public.course_technologies USING btree (course_id);


--
-- Name: course_technologies_technology_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX course_technologies_technology_id_index ON public.course_technologies USING btree (technology_id);


--
-- Name: courses_employee_level_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX courses_employee_level_id_index ON public.courses USING btree (employee_level_id);


--
-- Name: employee_course_completions_course_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX employee_course_completions_course_id_index ON public.employee_course_completions USING btree (course_id);


--
-- Name: employee_course_completions_employee_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX employee_course_completions_employee_id_index ON public.employee_course_completions USING btree (employee_id);


--
-- Name: employee_development_directions_development_direction_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX employee_development_directions_development_direction_id_index ON public.employee_development_directions USING btree (development_direction_id);


--
-- Name: employee_development_directions_employee_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX employee_development_directions_employee_id_index ON public.employee_development_directions USING btree (employee_id);


--
-- Name: employee_roadmaps_employee_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX employee_roadmaps_employee_id_index ON public.employee_roadmaps USING btree (employee_id);


--
-- Name: employee_roadmaps_manager_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX employee_roadmaps_manager_id_index ON public.employee_roadmaps USING btree (manager_id);


--
-- Name: employee_roadmaps_preset_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX employee_roadmaps_preset_id_index ON public.employee_roadmaps USING btree (preset_id);


--
-- Name: employee_technologies_employee_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX employee_technologies_employee_id_index ON public.employee_technologies USING btree (employee_id);


--
-- Name: employee_technologies_technology_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX employee_technologies_technology_id_index ON public.employee_technologies USING btree (technology_id);


--
-- Name: oauth_access_tokens_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_access_tokens_user_id_index ON public.oauth_access_tokens USING btree (user_id);


--
-- Name: oauth_auth_codes_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_auth_codes_user_id_index ON public.oauth_auth_codes USING btree (user_id);


--
-- Name: oauth_clients_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_clients_user_id_index ON public.oauth_clients USING btree (user_id);


--
-- Name: oauth_refresh_tokens_access_token_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_refresh_tokens_access_token_id_index ON public.oauth_refresh_tokens USING btree (access_token_id);


--
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);


--
-- Name: preset_courses_course_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX preset_courses_course_id_index ON public.preset_courses USING btree (course_id);


--
-- Name: preset_courses_preset_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX preset_courses_preset_id_index ON public.preset_courses USING btree (preset_id);


--
-- Name: presets_manager_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX presets_manager_id_index ON public.presets USING btree (manager_id);


--
-- Name: team_members_team_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX team_members_team_id_index ON public.team_members USING btree (team_id);


--
-- Name: team_members_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX team_members_user_id_index ON public.team_members USING btree (user_id);


--
-- Name: teams_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX teams_company_id_index ON public.teams USING btree (company_id);


--
-- Name: teams_owner_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX teams_owner_id_index ON public.teams USING btree (owner_id);


--
-- Name: technology_development_directions_development_direction_id_inde; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX technology_development_directions_development_direction_id_inde ON public.technology_development_directions USING btree (development_direction_id);


--
-- Name: technology_development_directions_technology_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX technology_development_directions_technology_id_index ON public.technology_development_directions USING btree (technology_id);


--
-- Name: users_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_company_id_index ON public.users USING btree (company_id);


--
-- Name: course_technologies course_technologies_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_technologies
    ADD CONSTRAINT course_technologies_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: course_technologies course_technologies_technology_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_technologies
    ADD CONSTRAINT course_technologies_technology_id_foreign FOREIGN KEY (technology_id) REFERENCES public.technologies(id) ON DELETE CASCADE;


--
-- Name: courses courses_employee_level_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_employee_level_id_foreign FOREIGN KEY (employee_level_id) REFERENCES public.employee_levels(id);


--
-- Name: employee_course_completions employee_course_completions_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_course_completions
    ADD CONSTRAINT employee_course_completions_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: employee_course_completions employee_course_completions_employee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_course_completions
    ADD CONSTRAINT employee_course_completions_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: employee_development_directions employee_development_directions_development_direction_id_foreig; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_development_directions
    ADD CONSTRAINT employee_development_directions_development_direction_id_foreig FOREIGN KEY (development_direction_id) REFERENCES public.development_directions(id);


--
-- Name: employee_development_directions employee_development_directions_employee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_development_directions
    ADD CONSTRAINT employee_development_directions_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES public.users(id);


--
-- Name: employee_roadmaps employee_roadmaps_employee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_roadmaps
    ADD CONSTRAINT employee_roadmaps_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES public.users(id);


--
-- Name: employee_roadmaps employee_roadmaps_manager_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_roadmaps
    ADD CONSTRAINT employee_roadmaps_manager_id_foreign FOREIGN KEY (manager_id) REFERENCES public.users(id);


--
-- Name: employee_roadmaps employee_roadmaps_preset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_roadmaps
    ADD CONSTRAINT employee_roadmaps_preset_id_foreign FOREIGN KEY (preset_id) REFERENCES public.presets(id);


--
-- Name: employee_technologies employee_technologies_employee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_technologies
    ADD CONSTRAINT employee_technologies_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: employee_technologies employee_technologies_technology_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_technologies
    ADD CONSTRAINT employee_technologies_technology_id_foreign FOREIGN KEY (technology_id) REFERENCES public.technologies(id) ON DELETE CASCADE;


--
-- Name: preset_courses preset_courses_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.preset_courses
    ADD CONSTRAINT preset_courses_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: preset_courses preset_courses_preset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.preset_courses
    ADD CONSTRAINT preset_courses_preset_id_foreign FOREIGN KEY (preset_id) REFERENCES public.presets(id) ON DELETE CASCADE;


--
-- Name: presets presets_manager_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.presets
    ADD CONSTRAINT presets_manager_id_foreign FOREIGN KEY (manager_id) REFERENCES public.users(id);


--
-- Name: team_members team_members_team_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.team_members
    ADD CONSTRAINT team_members_team_id_foreign FOREIGN KEY (team_id) REFERENCES public.teams(id) ON DELETE CASCADE;


--
-- Name: team_members team_members_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.team_members
    ADD CONSTRAINT team_members_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: teams teams_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teams
    ADD CONSTRAINT teams_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id);


--
-- Name: teams teams_owner_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teams
    ADD CONSTRAINT teams_owner_id_foreign FOREIGN KEY (owner_id) REFERENCES public.users(id);


--
-- Name: technology_development_directions technology_development_directions_development_direction_id_fore; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.technology_development_directions
    ADD CONSTRAINT technology_development_directions_development_direction_id_fore FOREIGN KEY (development_direction_id) REFERENCES public.development_directions(id);


--
-- Name: technology_development_directions technology_development_directions_technology_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.technology_development_directions
    ADD CONSTRAINT technology_development_directions_technology_id_foreign FOREIGN KEY (technology_id) REFERENCES public.technologies(id);


--
-- Name: users users_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id);


--
-- PostgreSQL database dump complete
--

INSERT INTO public.migrations VALUES (1, '2013_09_07_181545_create_development_directions_table', 1);
INSERT INTO public.migrations VALUES (2, '2013_09_07_182114_create_employee_levels_table', 1);
INSERT INTO public.migrations VALUES (3, '2013_09_08_053914_create_companies_table', 1);
INSERT INTO public.migrations VALUES (4, '2014_10_12_000000_create_users_table', 1);
INSERT INTO public.migrations VALUES (5, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO public.migrations VALUES (6, '2015_09_07_171813_create_teams_table', 1);
INSERT INTO public.migrations VALUES (7, '2016_06_01_000001_create_oauth_auth_codes_table', 1);
INSERT INTO public.migrations VALUES (8, '2016_06_01_000002_create_oauth_access_tokens_table', 1);
INSERT INTO public.migrations VALUES (9, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1);
INSERT INTO public.migrations VALUES (10, '2016_06_01_000004_create_oauth_clients_table', 1);
INSERT INTO public.migrations VALUES (11, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1);
INSERT INTO public.migrations VALUES (12, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO public.migrations VALUES (13, '2020_09_07_161311_create_technologies_table', 1);
INSERT INTO public.migrations VALUES (14, '2020_09_07_162059_create_courses_table', 1);
INSERT INTO public.migrations VALUES (15, '2020_09_07_162944_create_presets_table', 1);
INSERT INTO public.migrations VALUES (16, '2020_09_07_163319_create_employee_roadmaps_table', 1);
INSERT INTO public.migrations VALUES (17, '2020_09_07_163327_create_employee_course_completions_table', 1);
INSERT INTO public.migrations VALUES (18, '2020_09_07_173017_create_preset_courses_table', 1);
INSERT INTO public.migrations VALUES (19, '2020_09_07_173944_create_team_members_table', 1);
INSERT INTO public.migrations VALUES (20, '2020_09_07_180958_create_employee_technologies_table', 1);
INSERT INTO public.migrations VALUES (21, '2020_09_09_150026_create_employee_development_directions_table', 1);
INSERT INTO public.migrations VALUES (22, '2020_09_10_065837_create_technology_development_directions_table', 1);
INSERT INTO public.migrations VALUES (23, '2020_09_10_070100_create_course_technologies_table', 1);
