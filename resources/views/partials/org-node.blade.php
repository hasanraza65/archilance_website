{{-- Recursive chart node. $node is a TeamMember with childrenTree loaded. --}}
<li>
  @include('partials.org-card', ['m' => $node])
  @if($node->childrenTree->isNotEmpty())
    <ul>
      @foreach($node->childrenTree as $child)
        @include('partials.org-node', ['node' => $child])
      @endforeach
    </ul>
  @endif
</li>
