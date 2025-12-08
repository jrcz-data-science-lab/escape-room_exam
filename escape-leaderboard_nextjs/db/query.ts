import { getDb } from './db';

export async function run(sql: string, params: any[] = []) {
  const db = await getDb();
  return db.run(sql, params);
}

export async function all(sql: string, params: any[] = []) {
  const db = await getDb();
  return db.all(sql, params);
}

export async function get(sql: string, params: any[] = []) {
  const db = await getDb();
  return db.get(sql, params);
}

export default { run, all, get };
