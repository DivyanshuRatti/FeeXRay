USE feexray;

-- TEST DATA ONLY. Replace with real funds from Fund Facts before the demo.
INSERT INTO funds (name, fund_code, provider, asset_class, mer, mer_as_of) VALUES
('Test Fund A (high fee)', 'TEST001', 'Test Provider', 'Canadian Equity', 0.0225, '2026-06-30'),
('Test Fund B (low fee)',  'TEST002', 'Test Provider', 'Global Equity',   0.0020, '2026-06-30'),
('Test Fund C (mid fee)',  'TEST003', 'Test Provider', 'Balanced',        0.0125, '2026-06-30');