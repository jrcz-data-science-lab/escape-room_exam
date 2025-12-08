import { NextResponse } from 'next/server';
import { run } from '@/db/query';
import { cookies } from 'next/headers';

const ADMIN_COOKIE = 'escape_admin';

export async function DELETE(
  request: Request,
  context: { params: Promise<{ id: string }> }
) {
  // ✅ eerst de id ophalen
  const { id } = await context.params;

  // Valideer en parseer id als integer om SQL-injectie en verkeerde waarden te voorkomen
  const idNum = Number(id);
  if (!Number.isInteger(idNum) || idNum <= 0) {
    return NextResponse.json({ error: 'Invalid id' }, { status: 400 });
  }

  // Cookies ophalen (await omdat `cookies()` een Promise kan teruggeven in deze omgeving)
  const cookieStore = await cookies();
  const val = cookieStore.get(ADMIN_COOKIE)?.value;

  // ✅ check of admin is ingelogd
  if (!val || val !== process.env.ADMIN_PASSWORD) {
    return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
  }

  try {
    // Gebruik parameterbinding met centrale helper
    await run('DELETE FROM scores WHERE id = ?', [idNum]);
    return NextResponse.json({ ok: true });
  } catch (err) {
    console.error('Database error:', err);
    return NextResponse.json({ error: 'Server error' }, { status: 500 });
  }
}
