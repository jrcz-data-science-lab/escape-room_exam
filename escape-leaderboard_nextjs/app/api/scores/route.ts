// app/api/scores/route.ts
import { NextResponse } from 'next/server';
import { all, run, get } from '@/db/query';

const SCORE_TOKEN = process.env.SCORE_TOKEN;

// Functie voor het ophalen van scores
export async function GET() {
  const rows = await all('SELECT * FROM scores ORDER BY score DESC, created_at ASC');
  return NextResponse.json(rows);
}

// Functie voor het toevoegen van een score
export async function POST(request: Request) {
  try {
    const body = await request.json();
    const { name, score, token } = body;

    // Token validatie
    if (token !== SCORE_TOKEN) {
      return NextResponse.json({ error: 'Invalid token' }, { status: 403 });
    }

    // Invoer validatie
    if (!name || typeof score !== 'number' || name.length > 15 || score < 0 || score > 1000) {
      if (!name || name.length > 15) {
        return NextResponse.json({ error: 'Name must be provided and less than 15 characters' }, { status: 400 });
      }
      return NextResponse.json({ error: 'Score must be a number between 0 and 1000' }, { status: 400 });
    }

    const result = await run('INSERT INTO scores (name, score) VALUES (?, ?)', [name, score]);
    const inserted = await get('SELECT * FROM scores WHERE id = ?', [result.lastID]);
    return NextResponse.json(inserted, { status: 201 });
  } catch (err) {
    console.error(err);
    return NextResponse.json({ error: 'Server error' }, { status: 500 });
  }
}
