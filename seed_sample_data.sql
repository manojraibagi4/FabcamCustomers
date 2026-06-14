-- ============================================================
-- Fabcam Technologies — Sample Data: 50 Customers + Licenses
-- Run AFTER db.sql (users and products must exist)
-- Safe to re-run: cleans up previous sample data first
-- Current reference date: 2026-06-14
-- ============================================================

-- ----------------------------------------
-- CLEANUP previous sample run (safe to re-run)
-- ----------------------------------------
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_SAFE_UPDATES = 0;
DELETE FROM licenses  WHERE customer_id IN (SELECT id FROM customers WHERE customer_id REGEXP '^FAB-000[4-9]$|^FAB-00[1-4][0-9]$|^FAB-005[0-3]$');
DELETE FROM customers WHERE customer_id REGEXP '^FAB-000[4-9]$|^FAB-00[1-4][0-9]$|^FAB-005[0-3]$';
SET SQL_SAFE_UPDATES = 1;
SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------------------
-- 50 CUSTOMERS (FAB-0004 to FAB-0053)
-- ----------------------------------------
INSERT INTO customers (customer_id, company_name, contact_person, mobile, email, gst_number, address, created_by) VALUES
('FAB-0004', 'Alpha Tech Industries',       'Ramesh Gupta',        '9810012345', 'ramesh@alphatech.in',       '07AABCA1234B1ZP', '12, Connaught Place, New Delhi 110001',               1),
('FAB-0005', 'Beta Software Pvt Ltd',       'Sunita Joshi',        '9823456781', 'sunita@betasoftware.in',    '27AABCB2345C1ZQ', '56, FC Road, Pune, Maharashtra 411004',               1),
('FAB-0006', 'Gamma Retail Chains',         'Vijay Patel',         '9898123456', 'vijay@gammaretail.in',      '24AABCG3456D1ZR', '88, Ring Road, Surat, Gujarat 395001',                1),
('FAB-0007', 'Delta Manufacturing Ltd',     'Kiran Nair',          '9447001234', 'kiran@deltamfg.in',         '32AABCD4567E1ZS', '34, MG Road, Kochi, Kerala 682001',                   1),
('FAB-0008', 'Epsilon Logistics Pvt Ltd',   'Arjun Reddy',         '9866543210', 'arjun@epsilonlogistics.in', '36AABCE5678F1ZT', '101, Jubilee Hills, Hyderabad, Telangana 500033',     1),
('FAB-0009', 'Zeta Systems Ltd',            'Meena Iyer',          '9444212345', 'meena@zetasystems.in',      '33AABCZ6789G1ZU', '23, Anna Salai, Chennai, Tamil Nadu 600002',          1),
('FAB-0010', 'Eta Enterprises',             'Rahul Singh',         '9829034567', 'rahul@etaenterprise.in',    '08AABCE7890H1ZV', '7, MI Road, Jaipur, Rajasthan 302001',                1),
('FAB-0011', 'Theta Solutions Pvt Ltd',     'Pooja Agarwal',       '9451023456', 'pooja@thetasol.in',         '09AABCT8901I1ZW', '45, Hazratganj, Lucknow, Uttar Pradesh 226001',       1),
('FAB-0012', 'Iota Commerce Ltd',           'Suresh Rao',          '9845234567', 'suresh@iotacommerce.in',    '29AABCI9012J1ZX', '67, Brigade Road, Bengaluru, Karnataka 560025',       1),
('FAB-0013', 'Kappa Distributors',          'Anjali Shah',         '9824561234', 'anjali@kappadist.in',       '24AABCK0123K1ZY', '19, CG Road, Ahmedabad, Gujarat 380009',              1),
('FAB-0014', 'Lambda Technologies',         'Ravi Kumar',          '9833456123', 'ravi@lambdatech.in',        '27AABCL1234L1ZZ', '88, Linking Road, Mumbai, Maharashtra 400054',        1),
('FAB-0015', 'Mu Digital Services',         'Deepa Pillai',        '9447890123', 'deepa@mudigital.in',        '32AABCM2345M1ZA', '12, Sasthamangalam, Thiruvananthapuram, Kerala 695010',1),
('FAB-0016', 'Nu Electronics Pvt Ltd',      'Anil Sharma',         '9910234567', 'anil@nuelectronics.in',     '09AABCN3456N1ZB', 'A-45, Sector 62, Noida, Uttar Pradesh 201301',        1),
('FAB-0017', 'Xi Pharma Ltd',               'Kavita Desai',        '9822011234', 'kavita@xipharma.in',        '27AABCX4567O1ZC', '34, Wardha Road, Nagpur, Maharashtra 440010',         1),
('FAB-0018', 'Omicron Textiles',            'Mohan Das',           '9894512345', 'mohan@omicrontex.in',       '33AABCO5678P1ZD', '78, Avinashi Road, Coimbatore, Tamil Nadu 641018',    1),
('FAB-0019', 'Pi Constructions Pvt Ltd',    'Nita Mehta',          '9825678901', 'nita@piconst.in',           '24AABCP6789Q1ZE', '56, Ghod Dod Road, Surat, Gujarat 395007',            1),
('FAB-0020', 'Rho Foods Pvt Ltd',           'Sanjay Bhat',         '9845901234', 'sanjay@rhofoods.in',        '29AABCR7890R1ZF', '22, Kodialbail, Mangalore, Karnataka 575003',         1),
('FAB-0021', 'Sigma Steel Mills',           'Usha Rani',           '9866012345', 'usha@sigmasteel.in',        '37AABCS8901S1ZG', '45, Steel Plant Road, Visakhapatnam, Andhra Pradesh 530031', 1),
('FAB-0022', 'Tau Automotives Ltd',         'Vinod Tiwari',        '9826123456', 'vinod@tauauto.in',          '23AABCT9012T1ZH', '67, Palasia, Indore, Madhya Pradesh 452001',          1),
('FAB-0023', 'Upsilon Chemicals',           'Rekha Nair',          '9447123456', 'rekha@upsilonchem.in',      '32AABCU0123U1ZI', '89, Pattom, Thiruvananthapuram, Kerala 695004',       1),
('FAB-0024', 'Phi Healthcare Pvt Ltd',      'Manoj Verma',         '9814234567', 'manoj@phihealth.in',        '06AABCP1234V1ZJ', 'SCO 23, Sector 17, Chandigarh 160017',                1),
('FAB-0025', 'Chi Logistics Ltd',           'Geeta Patel',         '9825345678', 'geeta@chilogistics.in',     '24AABCC2345W1ZK', '12, Race Course, Vadodara, Gujarat 390007',           1),
('FAB-0026', 'Psi Textiles Pvt Ltd',        'Ashok Jain',          '9829456789', 'ashok@psitextiles.in',      '08AABCP3456X1ZL', '34, Bapu Nagar, Jaipur, Rajasthan 302015',            1),
('FAB-0027', 'Omega Retail Ltd',            'Sushma Gupta',        '9454567890', 'sushma@omegaretail.in',     '09AABCO4567Y1ZM', '56, Sanjay Place, Agra, Uttar Pradesh 282002',        1),
('FAB-0028', 'Apex Industries Pvt Ltd',     'Dinesh Kumar',        '9826678901', 'dinesh@apexind.in',         '23AABCA5678Z1ZN', '78, Maharana Pratap Nagar, Bhopal, Madhya Pradesh 462011', 1),
('FAB-0029', 'Zenith Tech Corp',            'Preethi Rao',         '9844789012', 'preethi@zenithtech.in',     '29AABCZ6789A1ZO', '90, Saraswathipuram, Mysuru, Karnataka 570009',       1),
('FAB-0030', 'Vertex Solutions',            'Ramakrishna Rao',     '9866890123', 'rk.rao@vertexsol.in',       '37AABCV7890B1ZP', '12, MG Road, Vijayawada, Andhra Pradesh 520010',      1),
('FAB-0031', 'Nexus Commerce Pvt Ltd',      'Lalitha Devi',        '9866901234', 'lalitha@nexuscommerce.in',  '36AABCN8901C1ZQ', '34, Hanamkonda, Warangal, Telangana 506001',          1),
('FAB-0032', 'Fusion Digital Services',     'Harish Chandra',      '9837012345', 'harish@fusiondigital.in',   '05AABCF9012D1ZR', '56, Rajpur Road, Dehradun, Uttarakhand 248001',       1),
('FAB-0033', 'Horizon Enterprises Ltd',     'Shobha Iyer',         '9894123456', 'shobha@horizonent.in',      '33AABCH0123E1ZS', '78, Saradha College Road, Salem, Tamil Nadu 636016',  1),
('FAB-0034', 'Pinnacle Systems Pvt Ltd',    'Kishore Babu',        '9866234567', 'kishore@pinnaclesys.in',    '37AABCP1234F1ZT', '90, Main Road, Kakinada, Andhra Pradesh 533001',      1),
('FAB-0035', 'Summit Software Ltd',         'Nalini Reddy',        '9866345678', 'nalini@summitsw.in',        '36AABCS2345G1ZU', '12, Nizamabad Rd, Nizamabad, Telangana 503001',       1),
('FAB-0036', 'Crest Technologies',          'Subramaniam K',       '9443456789', 'subramani@cresttech.in',    '33AABCC3456H1ZV', '34, Mattuthavani, Madurai, Tamil Nadu 625007',        1),
('FAB-0037', 'Peak Distributors Pvt Ltd',   'Jayalakshmi S',       '9843567890', 'jayalakshmi@peakdist.in',   '33AABCP4567I1ZW', '56, Avinashi Road, Tiruppur, Tamil Nadu 641601',      1),
('FAB-0038', 'Ridge Retail Ltd',            'Murali Krishnan',     '9447678901', 'murali@ridgeretail.in',     '32AABCR5678J1ZX', '78, Round North, Thrissur, Kerala 680001',            1),
('FAB-0039', 'Zenon Electronics Pvt Ltd',   'Padmavathi G',        '9866789012', 'padmavathi@zenonelec.in',   '37AABCZ6789K1ZY', '90, Brodipet, Guntur, Andhra Pradesh 522002',         1),
('FAB-0040', 'Vortex Pharma Ltd',           'Balasubramanian T',   '9431890123', 'bala@vortexpharma.in',      '20AABCV7890L1ZZ', '12, Bank More, Dhanbad, Jharkhand 826001',            1),
('FAB-0041', 'Astral Systems Pvt Ltd',      'Chandrakala Singh',   '9835901234', 'chandrakala@astralsys.in',  '20AABCA8901M1ZA', '34, Circular Road, Ranchi, Jharkhand 834001',         1),
('FAB-0042', 'Stellar Commerce Ltd',        'Venkataraman P',      '9894012345', 'venkat@stellarcom.in',      '33AABCS9012N1ZB', '56, South Car Street, Tirunelveli, Tamil Nadu 627001', 1),
('FAB-0043', 'Cosmic Industries',           'Laxmidevi Naik',      '9845123456', 'laxmi@cosmicinds.in',       '29AABCC0123O1ZC', '78, Vidyanagar, Hubli, Karnataka 580031',             1),
('FAB-0044', 'Solar Tech Pvt Ltd',          'Gopal Krishnan',      '9844234567', 'gopal@solartech.in',        '29AABCS1234P1ZD', '12, Balmatta Road, Mangalore, Karnataka 575001',      1),
('FAB-0045', 'Lunar Software Services',     'Savitri Devi',        '9815345678', 'savitri@lunarsw.in',        '03AABCL2345Q1ZE', '34, Lawrence Road, Amritsar, Punjab 143001',          1),
('FAB-0046', 'Mercury Digital Pvt Ltd',     'Harpreet Singh',      '9815456789', 'harpreet@mercurydig.in',    '03AABCM3456R1ZF', '56, Ferozepur Road, Ludhiana, Punjab 141001',         1),
('FAB-0047', 'Venus Retail Ltd',            'Gurpreet Kaur',       '9815567890', 'gurpreet@venusretail.in',   '03AABCV4567S1ZG', '78, BMC Chowk, Jalandhar, Punjab 144001',             1),
('FAB-0048', 'Mars Manufacturing Pvt Ltd',  'Balwinder Singh',     '9815678901', 'balwinder@marsmfg.in',      '03AABCM5678T1ZH', '90, The Mall, Patiala, Punjab 147001',                1),
('FAB-0049', 'Saturn Systems Ltd',          'Satinder Kumar',      '9816789012', 'satinder@saturnsys.in',     '02AABCS6789U1ZI', '12, The Mall, Shimla, Himachal Pradesh 171001',       1),
('FAB-0050', 'Jupiter Commerce Pvt Ltd',    'Prem Chand Gupta',    '9419890123', 'prem@jupitercom.in',        '01AABCJ7890V1ZJ', '34, Residency Road, Jammu, J&K 180001',               1),
('FAB-0051', 'Neptune Logistics',           'Rajinder Singh',      '9419901234', 'rajinder@neptunelog.in',    '01AABCN8901W1ZK', '56, Dalhousie Road, Pathankot, Punjab 145001',        1),
('FAB-0052', 'Uranus Tech Ltd',             'Gurjeet Kaur',        '9815012345', 'gurjeet@uranustech.in',     '03AABCU9012X1ZL', '78, Goniana Road, Bathinda, Punjab 151001',           1),
('FAB-0053', 'Pluto Enterprises Pvt Ltd',   'Sukhwinder Pal',      '9815123456', 'sukhwinder@plutoent.in',    '03AABCP0123Y1ZM', '90, GT Road, Moga, Punjab 142001',                   1);


-- ----------------------------------------
-- 50 LICENSES (one per customer)
-- Mix: 25 active, 8 expiring soon, 10 expired, 4 grace, 3 revoked
-- ----------------------------------------

-- ACTIVE licenses (expiry 2027-2028)
INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 1, 'single', CONCAT('SRV-',c.customer_id), CONCAT('LCK-A1',c.id), CONCAT('WS-',c.customer_id), 45000.00, '2025-06-14', '2027-06-14', 'active', 4500.00, '2027-06-14', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0004';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 2, 'multi',  CONCAT('SRV-',c.customer_id), CONCAT('LCK-A2',c.id), CONCAT('WS-',c.customer_id), 30000.00, '2025-03-01', '2027-03-01', 'active', 3000.00, '2027-03-01', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0005';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 3, 'server', CONCAT('SRV-',c.customer_id), CONCAT('LCK-A3',c.id), CONCAT('SRV-',c.customer_id), 85000.00, '2025-01-15', '2027-01-15', 'active', 8500.00, '2027-01-15', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0006';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 1, 'cloud',  NULL, NULL, NULL, 60000.00, '2025-06-01', '2027-06-01', 'active', 6000.00, '2027-06-01', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0007';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 2, 'single', CONCAT('SRV-',c.customer_id), CONCAT('LCK-A5',c.id), CONCAT('WS-',c.customer_id), 28000.00, '2025-04-10', '2027-04-10', 'active', 2800.00, '2027-04-10', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0008';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 3, 'multi',  CONCAT('SRV-',c.customer_id), CONCAT('LCK-A6',c.id), CONCAT('WS-',c.customer_id), 50000.00, '2025-02-20', '2027-02-20', 'active', 5000.00, '2027-02-20', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0009';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 1, 'server', CONCAT('SRV-',c.customer_id), CONCAT('LCK-A7',c.id), CONCAT('SRV-',c.customer_id), 95000.00, '2024-12-01', '2026-12-01', 'active', 9500.00, '2026-12-01', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0010';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 2, 'single', CONCAT('SRV-',c.customer_id), CONCAT('LCK-A8',c.id), CONCAT('WS-',c.customer_id), 22000.00, '2025-05-15', '2027-05-15', 'active', 2200.00, '2027-05-15', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0011';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 3, 'cloud',  NULL, NULL, NULL, 40000.00, '2025-03-25', '2027-03-25', 'active', 4000.00, '2027-03-25', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0012';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 1, 'multi',  CONCAT('SRV-',c.customer_id), CONCAT('LCK-A10',c.id), CONCAT('WS-',c.customer_id), 75000.00, '2024-11-10', '2026-11-10', 'active', 7500.00, '2026-11-10', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0013';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 2, 'single', CONCAT('SRV-',c.customer_id), CONCAT('LCK-A11',c.id), CONCAT('WS-',c.customer_id), 18000.00, '2025-07-01', '2027-07-01', 'active', 1800.00, '2027-07-01', 'not_applicable', 1
FROM customers c WHERE c.customer_id = 'FAB-0014';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 3, 'server', CONCAT('SRV-',c.customer_id), CONCAT('LCK-A12',c.id), CONCAT('SRV-',c.customer_id), 110000.00, '2025-01-01', '2028-01-01', 'active', 11000.00, '2028-01-01', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0015';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 1, 'cloud',  NULL, NULL, NULL, 55000.00, '2025-04-01', '2027-04-01', 'active', 5500.00, '2027-04-01', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0016';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 2, 'multi',  CONCAT('SRV-',c.customer_id), CONCAT('LCK-A14',c.id), CONCAT('WS-',c.customer_id), 32000.00, '2025-02-01', '2027-02-01', 'active', 3200.00, '2027-02-01', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0017';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 3, 'single', CONCAT('SRV-',c.customer_id), CONCAT('LCK-A15',c.id), CONCAT('WS-',c.customer_id), 25000.00, '2025-06-01', '2027-06-01', 'active', 2500.00, '2027-06-01', 'not_applicable', 1
FROM customers c WHERE c.customer_id = 'FAB-0018';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 1, 'server', CONCAT('SRV-',c.customer_id), CONCAT('LCK-A16',c.id), CONCAT('SRV-',c.customer_id), 90000.00, '2024-10-15', '2026-10-15', 'active', 9000.00, '2026-10-15', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0019';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 2, 'cloud',  NULL, NULL, NULL, 48000.00, '2025-05-01', '2027-05-01', 'active', 4800.00, '2027-05-01', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0020';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 3, 'multi',  CONCAT('SRV-',c.customer_id), CONCAT('LCK-A18',c.id), CONCAT('WS-',c.customer_id), 65000.00, '2025-03-10', '2028-03-10', 'active', 6500.00, '2028-03-10', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0021';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 1, 'single', CONCAT('SRV-',c.customer_id), CONCAT('LCK-A19',c.id), CONCAT('WS-',c.customer_id), 20000.00, '2025-04-20', '2027-04-20', 'active', 2000.00, '2027-04-20', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0022';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 2, 'server', CONCAT('SRV-',c.customer_id), CONCAT('LCK-A20',c.id), CONCAT('SRV-',c.customer_id), 100000.00, '2025-01-20', '2028-01-20', 'active', 10000.00, '2028-01-20', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0023';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 3, 'cloud',  NULL, NULL, NULL, 35000.00, '2025-02-10', '2027-02-10', 'active', 3500.00, '2027-02-10', 'not_applicable', 1
FROM customers c WHERE c.customer_id = 'FAB-0024';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 1, 'multi',  CONCAT('SRV-',c.customer_id), CONCAT('LCK-A22',c.id), CONCAT('WS-',c.customer_id), 42000.00, '2024-09-01', '2026-09-01', 'active', 4200.00, '2026-09-01', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0025';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 2, 'single', CONCAT('SRV-',c.customer_id), CONCAT('LCK-A23',c.id), CONCAT('WS-',c.customer_id), 17000.00, '2025-05-25', '2027-05-25', 'active', 1700.00, '2027-05-25', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0026';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 3, 'server', CONCAT('SRV-',c.customer_id), CONCAT('LCK-A24',c.id), CONCAT('SRV-',c.customer_id), 80000.00, '2025-01-05', '2027-01-05', 'active', 8000.00, '2027-01-05', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0027';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 1, 'cloud',  NULL, NULL, NULL, 52000.00, '2025-03-15', '2027-03-15', 'active', 5200.00, '2027-03-15', 'active', 1
FROM customers c WHERE c.customer_id = 'FAB-0028';


-- EXPIRING SOON (within 30 days — 2026-06-15 to 2026-07-14)
INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, remarks, updated_by)
SELECT c.id, 1, 'single', CONCAT('SRV-',c.customer_id), CONCAT('LCK-E1',c.id), CONCAT('WS-',c.customer_id), 45000.00, '2024-06-20', '2026-06-20', 'active', 4500.00, '2026-06-20', 'active', 'Renewal due soon', 1
FROM customers c WHERE c.customer_id = 'FAB-0029';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, remarks, updated_by)
SELECT c.id, 2, 'multi',  CONCAT('SRV-',c.customer_id), CONCAT('LCK-E2',c.id), CONCAT('WS-',c.customer_id), 30000.00, '2024-06-25', '2026-06-25', 'active', 3000.00, '2026-06-25', 'active', 'Renewal due soon', 1
FROM customers c WHERE c.customer_id = 'FAB-0030';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, remarks, updated_by)
SELECT c.id, 3, 'server', CONCAT('SRV-',c.customer_id), CONCAT('LCK-E3',c.id), CONCAT('SRV-',c.customer_id), 85000.00, '2024-07-01', '2026-07-01', 'active', 8500.00, '2026-07-01', 'active', 'Renewal due soon', 1
FROM customers c WHERE c.customer_id = 'FAB-0031';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, remarks, updated_by)
SELECT c.id, 1, 'cloud',  NULL, NULL, NULL, 60000.00, '2024-07-05', '2026-07-05', 'active', 6000.00, '2026-07-05', 'active', 'Renewal due soon', 1
FROM customers c WHERE c.customer_id = 'FAB-0032';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, remarks, updated_by)
SELECT c.id, 2, 'single', CONCAT('SRV-',c.customer_id), CONCAT('LCK-E5',c.id), CONCAT('WS-',c.customer_id), 22000.00, '2024-07-10', '2026-07-10', 'active', 2200.00, '2026-07-10', 'active', 'Renewal due soon', 1
FROM customers c WHERE c.customer_id = 'FAB-0033';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, remarks, updated_by)
SELECT c.id, 3, 'multi',  CONCAT('SRV-',c.customer_id), CONCAT('LCK-E6',c.id), CONCAT('WS-',c.customer_id), 48000.00, '2024-07-12', '2026-07-12', 'active', 4800.00, '2026-07-12', 'active', 'Renewal due soon', 1
FROM customers c WHERE c.customer_id = 'FAB-0034';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, remarks, updated_by)
SELECT c.id, 1, 'server', CONCAT('SRV-',c.customer_id), CONCAT('LCK-E7',c.id), CONCAT('SRV-',c.customer_id), 75000.00, '2024-07-14', '2026-07-14', 'active', 7500.00, '2026-07-14', 'active', 'Renewal due soon', 1
FROM customers c WHERE c.customer_id = 'FAB-0035';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, remarks, updated_by)
SELECT c.id, 2, 'cloud',  NULL, NULL, NULL, 38000.00, '2024-06-16', '2026-06-16', 'active', 3800.00, '2026-06-16', 'active', 'Renewal due soon', 1
FROM customers c WHERE c.customer_id = 'FAB-0036';


-- EXPIRED licenses (expiry before 2026-06-14)
INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 1, 'single', CONCAT('SRV-',c.customer_id), CONCAT('LCK-X1',c.id), CONCAT('WS-',c.customer_id), 25000.00, '2023-01-01', '2025-01-01', 'expired', 2500.00, '2025-01-01', 'expired', 1
FROM customers c WHERE c.customer_id = 'FAB-0037';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 2, 'multi',  CONCAT('SRV-',c.customer_id), CONCAT('LCK-X2',c.id), CONCAT('WS-',c.customer_id), 32000.00, '2023-03-15', '2025-03-15', 'expired', 3200.00, '2025-03-15', 'expired', 1
FROM customers c WHERE c.customer_id = 'FAB-0038';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 3, 'server', CONCAT('SRV-',c.customer_id), CONCAT('LCK-X3',c.id), CONCAT('SRV-',c.customer_id), 80000.00, '2022-06-01', '2024-06-01', 'expired', 8000.00, '2024-06-01', 'expired', 1
FROM customers c WHERE c.customer_id = 'FAB-0039';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 1, 'cloud',  NULL, NULL, NULL, 42000.00, '2023-09-01', '2025-09-01', 'expired', 4200.00, '2025-09-01', 'expired', 1
FROM customers c WHERE c.customer_id = 'FAB-0040';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 2, 'single', CONCAT('SRV-',c.customer_id), CONCAT('LCK-X5',c.id), CONCAT('WS-',c.customer_id), 18000.00, '2023-11-01', '2025-11-01', 'expired', 1800.00, '2025-11-01', 'expired', 1
FROM customers c WHERE c.customer_id = 'FAB-0041';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 3, 'multi',  CONCAT('SRV-',c.customer_id), CONCAT('LCK-X6',c.id), CONCAT('WS-',c.customer_id), 55000.00, '2024-01-10', '2026-01-10', 'expired', 5500.00, '2026-01-10', 'expired', 1
FROM customers c WHERE c.customer_id = 'FAB-0042';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 1, 'server', CONCAT('SRV-',c.customer_id), CONCAT('LCK-X7',c.id), CONCAT('SRV-',c.customer_id), 90000.00, '2022-12-01', '2024-12-01', 'expired', 9000.00, '2024-12-01', 'expired', 1
FROM customers c WHERE c.customer_id = 'FAB-0043';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 2, 'cloud',  NULL, NULL, NULL, 36000.00, '2024-02-01', '2026-02-01', 'expired', 3600.00, '2026-02-01', 'expired', 1
FROM customers c WHERE c.customer_id = 'FAB-0044';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 3, 'single', CONCAT('SRV-',c.customer_id), CONCAT('LCK-X9',c.id), CONCAT('WS-',c.customer_id), 20000.00, '2024-03-01', '2026-03-01', 'expired', 2000.00, '2026-03-01', 'expired', 1
FROM customers c WHERE c.customer_id = 'FAB-0045';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, updated_by)
SELECT c.id, 1, 'multi',  CONCAT('SRV-',c.customer_id), CONCAT('LCK-X10',c.id), CONCAT('WS-',c.customer_id), 47000.00, '2024-04-01', '2026-04-01', 'expired', 4700.00, '2026-04-01', 'expired', 1
FROM customers c WHERE c.customer_id = 'FAB-0046';


-- GRACE period licenses
INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, remarks, updated_by)
SELECT c.id, 2, 'single', CONCAT('SRV-',c.customer_id), CONCAT('LCK-G1',c.id), CONCAT('WS-',c.customer_id), 22000.00, '2024-05-01', '2026-05-01', 'grace', 2200.00, '2026-05-01', 'expired', 'In grace period — pending renewal', 1
FROM customers c WHERE c.customer_id = 'FAB-0047';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, remarks, updated_by)
SELECT c.id, 3, 'multi',  CONCAT('SRV-',c.customer_id), CONCAT('LCK-G2',c.id), CONCAT('WS-',c.customer_id), 58000.00, '2024-04-15', '2026-04-15', 'grace', 5800.00, '2026-04-15', 'expired', 'In grace period — pending renewal', 1
FROM customers c WHERE c.customer_id = 'FAB-0048';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, remarks, updated_by)
SELECT c.id, 1, 'server', CONCAT('SRV-',c.customer_id), CONCAT('LCK-G3',c.id), CONCAT('SRV-',c.customer_id), 95000.00, '2024-03-10', '2026-03-10', 'grace', 9500.00, '2026-03-10', 'expired', 'In grace period — pending renewal', 1
FROM customers c WHERE c.customer_id = 'FAB-0049';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, remarks, updated_by)
SELECT c.id, 2, 'cloud',  NULL, NULL, NULL, 40000.00, '2024-05-20', '2026-05-20', 'grace', 4000.00, '2026-05-20', 'expired', 'In grace period — pending renewal', 1
FROM customers c WHERE c.customer_id = 'FAB-0050';


-- REVOKED licenses
INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, remarks, updated_by)
SELECT c.id, 3, 'single', CONCAT('SRV-',c.customer_id), CONCAT('LCK-R1',c.id), CONCAT('WS-',c.customer_id), 15000.00, '2023-06-01', '2025-06-01', 'revoked', NULL, NULL, 'not_applicable', 'License revoked — non-payment', 1
FROM customers c WHERE c.customer_id = 'FAB-0051';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, remarks, updated_by)
SELECT c.id, 1, 'multi',  CONCAT('SRV-',c.customer_id), CONCAT('LCK-R2',c.id), CONCAT('WS-',c.customer_id), 28000.00, '2022-08-01', '2024-08-01', 'revoked', NULL, NULL, 'not_applicable', 'License revoked — contract terminated', 1
FROM customers c WHERE c.customer_id = 'FAB-0052';

INSERT INTO licenses (customer_id, product_id, license_type, server_code, lock_code, machine_name, purchase_price, purchase_date, expiry_date, license_status, amc_cost, renewal_date, amc_status, remarks, updated_by)
SELECT c.id, 2, 'server', CONCAT('SRV-',c.customer_id), CONCAT('LCK-R3',c.id), CONCAT('SRV-',c.customer_id), 70000.00, '2021-12-01', '2023-12-01', 'revoked', NULL, NULL, 'not_applicable', 'License revoked — business closed', 1
FROM customers c WHERE c.customer_id = 'FAB-0053';

-- ============================================================
-- Summary after import:
--   Customers  : 53 total (3 existing + 50 new)
--   Licenses   : 57 total (4 existing + 53 new... wait 50 new)
--   Active     : 25
--   Expiring ≤30d: 8
--   Expired    : 10
--   Grace      : 4
--   Revoked    : 3
-- ============================================================
